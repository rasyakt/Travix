<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Models\Flight;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PaymentWebhookController extends Controller
{
    public function handleMidtrans(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|string',
            'status_code' => 'required|string',
            'gross_amount' => 'required',
            'signature_key' => 'required|string',
            'transaction_status' => 'required|string',
        ]);

        $serverKey = (string) config('services.midtrans.server_key');
        if ($serverKey === '') {
            return response()->json(['message' => 'Midtrans server key is not configured.'], 503);
        }

        $orderId = (string) $request->input('order_id');
        $statusCode = (string) $request->input('status_code');
        $grossAmount = (string) $request->input('gross_amount');
        $signatureKey = (string) $request->input('signature_key');

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        if (!hash_equals($expectedSignature, $signatureKey)) {
            return response()->json(['message' => 'Invalid Midtrans signature.'], 403);
        }

        $webhookLock = Cache::lock("midtrans:webhook:{$orderId}", 10);
        if (!$webhookLock->get()) {
            return response()->json(['message' => 'Webhook is already being processed.'], 202);
        }

        try {
            $result = DB::transaction(function () use ($request, $orderId) {
                /** @var Payment|null $payment */
                $payment = Payment::with(['booking.bookingFlights.flight'])
                    ->where(function ($query) use ($orderId) {
                        $query->where('midtrans_order_id', $orderId)
                            ->orWhere('payment_code', $orderId);
                    })
                    ->lockForUpdate()
                    ->first();

                if (!$payment) {
                    return ['status' => 404, 'message' => 'Payment not found.'];
                }

                $paymentDetails = $payment->payment_details ?? [];
                $processedWebhookIds = array_values(array_filter((array) ($paymentDetails['processed_webhooks'] ?? [])));

                $eventId = (string) ($request->input('transaction_id') ?: sha1($request->getContent()));
                if (in_array($eventId, $processedWebhookIds, true)) {
                    return ['status' => 200, 'message' => 'Webhook already processed.'];
                }

                $targetStatus = $this->mapMidtransStatus(
                    (string) $request->input('transaction_status'),
                    (string) $request->input('fraud_status', '')
                );

                if (!$this->canTransitionStatus($payment->status, $targetStatus)) {
                    $processedWebhookIds[] = $eventId;
                    $paymentDetails['processed_webhooks'] = array_slice($processedWebhookIds, -20);
                    $paymentDetails['last_midtrans_webhook'] = [
                        'event_id' => $eventId,
                        'transaction_status' => $request->input('transaction_status'),
                        'fraud_status' => $request->input('fraud_status'),
                        'handled_at' => now()->toDateTimeString(),
                        'ignored' => true,
                    ];

                    $payment->update(['payment_details' => $paymentDetails]);

                    return ['status' => 200, 'message' => 'Webhook ignored because status is terminal.'];
                }

                $inventoryReserved = (bool) ($paymentDetails['inventory_reserved'] ?? false);

                if ($targetStatus === PaymentStatus::SUCCESS->value && !$inventoryReserved) {
                    $booking = $payment->booking;

                    if ($booking) {
                        foreach ($booking->bookingFlights as $bookingFlight) {
                            $lockedFlight = Flight::where('id', $bookingFlight->flight_id)
                                ->lockForUpdate()
                                ->first();

                            if (!$lockedFlight || !$lockedFlight->decreaseAvailableSeats((int) ($bookingFlight->passenger_count ?? 1), $bookingFlight->travel_class_id)) {
                                throw new \RuntimeException('Seat inventory is no longer available while processing payment webhook.');
                            }
                        }

                        $booking->update(['status' => BookingStatus::CONFIRMED->value]);
                    }

                    $paymentDetails['inventory_reserved'] = true;
                    $paymentDetails['inventory_reserved_at'] = now()->toDateTimeString();
                }

                $processedWebhookIds[] = $eventId;
                $paymentDetails['processed_webhooks'] = array_slice($processedWebhookIds, -20);
                $paymentDetails['last_midtrans_webhook'] = [
                    'event_id' => $eventId,
                    'transaction_status' => $request->input('transaction_status'),
                    'fraud_status' => $request->input('fraud_status'),
                    'handled_at' => now()->toDateTimeString(),
                ];

                $updatePayload = [
                    'status' => $targetStatus,
                    'midtrans_order_id' => $orderId,
                    'midtrans_transaction_id' => $request->input('transaction_id'),
                    'payment_details' => $paymentDetails,
                ];

                if ($targetStatus === PaymentStatus::SUCCESS->value && !$payment->paid_at) {
                    $updatePayload['paid_at'] = now();
                }

                $payment->update($updatePayload);

                return ['status' => 200, 'message' => 'Webhook processed successfully.'];
            });

            return response()->json(['message' => $result['message']], $result['status']);
        } catch (\Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'Failed to process webhook.',
            ], 500);
        } finally {
            optional($webhookLock)->release();
        }
    }

    private function mapMidtransStatus(string $transactionStatus, string $fraudStatus = ''): string
    {
        return match ($transactionStatus) {
            'capture' => $fraudStatus === 'challenge'
                ? PaymentStatus::PROCESSING->value
                : PaymentStatus::SUCCESS->value,
            'settlement' => PaymentStatus::SUCCESS->value,
            'pending' => PaymentStatus::PENDING->value,
            'deny' => PaymentStatus::FAILED->value,
            'expire' => PaymentStatus::EXPIRED->value,
            'cancel' => PaymentStatus::CANCELLED->value,
            default => PaymentStatus::PROCESSING->value,
        };
    }

    private function canTransitionStatus(string $currentStatus, string $targetStatus): bool
    {
        if ($currentStatus === $targetStatus) {
            return true;
        }

        $terminalStatuses = [
            PaymentStatus::SUCCESS->value,
            PaymentStatus::EXPIRED->value,
            PaymentStatus::CANCELLED->value,
        ];

        if (in_array($currentStatus, $terminalStatuses, true)) {
            return false;
        }

        return true;
    }
}
