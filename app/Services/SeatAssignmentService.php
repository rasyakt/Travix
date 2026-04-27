<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\FlightSeatPrice;
use App\Models\SeatAssignment;
use App\Models\SeatMap;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SeatAssignmentService
{
    public function assignSeats(Booking $booking, int $travelClassId, array $selectedSeatIds): void
    {
        DB::transaction(function () use ($booking, $travelClassId, $selectedSeatIds) {
            /** @var Booking $lockedBooking */
            $lockedBooking = Booking::with(['bookingFlights', 'passengers', 'payment'])
                ->where('id', $booking->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!in_array($lockedBooking->status, ['pending', 'confirmed'], true)) {
                throw new RuntimeException('Seat assignment is not available for this booking status.');
            }

            $flight = $lockedBooking->flight;
            if (!$flight) {
                throw new RuntimeException('No flight found for this booking.');
            }

            $passengerIds = $lockedBooking->passengers->pluck('id')->values()->all();

            if (count($selectedSeatIds) !== count($passengerIds)) {
                throw new RuntimeException('Jumlah kursi yang dipilih tidak sesuai dengan jumlah penumpang.');
            }

            if (count(array_unique($selectedSeatIds)) !== count($selectedSeatIds)) {
                throw new RuntimeException('Duplicate seat selection detected.');
            }

            $seatMapById = SeatMap::whereIn('id', $selectedSeatIds)
                ->where('travel_class_id', $travelClassId)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($seatMapById->count() !== count($selectedSeatIds)) {
                throw new RuntimeException('Satu atau lebih kursi tidak valid untuk kelas perjalanan yang dipilih.');
            }

            $takenSeatIds = SeatAssignment::where('flight_id', $flight->id)
                ->whereIn('seat_map_id', $selectedSeatIds)
                ->whereNotIn('booking_passenger_id', $passengerIds)
                ->lockForUpdate()
                ->pluck('seat_map_id')
                ->all();

            if (!empty($takenSeatIds)) {
                throw new RuntimeException('Satu atau lebih kursi baru saja diambil pengguna lain.');
            }

            // FIX: Calculate total seat extra fees
            $totalSeatExtraFee = 0;

            foreach ($selectedSeatIds as $index => $seatId) {
                $passengerId = $passengerIds[$index] ?? null;
                $seat = $seatMapById->get($seatId);

                if (!$passengerId || !$seat || !$seat->is_available) {
                    throw new RuntimeException('Pilihan kursi tidak valid.');
                }

                // FIX: Add extra fee to total
                $extraFee = (float) ($seat->extra_price ?? 0);
                $totalSeatExtraFee += $extraFee;

                SeatAssignment::updateOrCreate(
                    [
                        'booking_passenger_id' => $passengerId,
                        'flight_id' => $flight->id,
                    ],
                    [
                        'seat_map_id' => $seat->id,
                        'seat_number' => $seat->seat_number,
                        'extra_fee' => $extraFee, // FIX: Store extra fee
                        'assigned_at' => now(),
                    ]
                );
            }

            $selectedClassPrice = FlightSeatPrice::where('flight_id', $flight->id)
                ->where('travel_class_id', $travelClassId)
                ->lockForUpdate()
                ->first();

            $pricePerPassenger = $selectedClassPrice?->price ?? 0;
            $newTotal = (float) $pricePerPassenger * count($passengerIds);
            
            // FIX: Add taxes and seat fees to total
            $taxesFees = $newTotal * 0.10; // 10% tax

            $bookingFlight = $lockedBooking->bookingFlights->first();
            if ($bookingFlight) {
                $bookingFlight->update([
                    'travel_class_id' => $travelClassId,
                    'price_per_passenger' => $pricePerPassenger,
                    'total_price' => $newTotal,
                    'passenger_count' => count($passengerIds),
                ]);
            }

            // FIX: Update booking with all fees
            $lockedBooking->update([
                'base_fare' => $newTotal,
                'taxes_fees' => $taxesFees,
                'seat_fee' => $totalSeatExtraFee,
                'total_amount' => $newTotal + $taxesFees + $totalSeatExtraFee,
            ]);

            if ($lockedBooking->payment && in_array($lockedBooking->payment->status, ['pending', 'failed', 'processing'], true)) {
                $lockedBooking->payment->update([
                    'amount' => $lockedBooking->total_amount,
                ]);
            }
        });
    }
}
