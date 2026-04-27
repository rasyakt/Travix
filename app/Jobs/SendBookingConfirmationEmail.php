<?php

namespace App\Jobs;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendBookingConfirmationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $booking;
    public $tries = 3;
    public $timeout = 60;
    public $backoff = [10, 30, 60]; // Retry after 10s, 30s, 60s

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function handle(): void
    {
        try {
            // Load relationships
            $this->booking->load(['passengers', 'payment', 'flights']);

            $isApiBooking = $this->booking->flights->isEmpty() && 
                           $this->booking->payment && 
                           isset($this->booking->payment->payment_details['source']) && 
                           $this->booking->payment->payment_details['source'] === 'api_partner';

            $flightData = $isApiBooking 
                ? $this->booking->payment->payment_details['flight_data'] 
                : null;

            // Send email (implement your mail class)
            Mail::to($this->booking->contact_email)->send(
                new \App\Mail\BookingConfirmation($this->booking, $isApiBooking, $flightData)
            );

            Log::info('Booking confirmation email sent', [
                'booking_id' => $this->booking->id,
                'email' => $this->booking->contact_email,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send booking confirmation email', [
                'booking_id' => $this->booking->id,
                'error' => $e->getMessage(),
            ]);

            throw $e; // Will trigger retry
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Booking confirmation email failed permanently', [
            'booking_id' => $this->booking->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
