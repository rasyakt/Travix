<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;

// Home
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Google OAuth
Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);
Route::post('/logout', [SocialiteController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Flights
    Route::get('/flights', [FlightController::class, 'index'])->name('flights.index');
    Route::get('/flights/{id}', [FlightController::class, 'show'])->name('flights.show');
    Route::get('/flights/{id}/status', [FlightController::class, 'status'])->name('flights.status');
    Route::post('/flights/{id}/update-status', [FlightController::class, 'updateStatus'])->name('flights.update-status');

    // Bookings
    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
    Route::delete('/booking/{id}', [BookingController::class, 'cancel'])->name('booking.cancel');
    Route::get('/booking/{id}/payment', [BookingController::class, 'payment'])->name('booking.payment');
    Route::post('/booking/{id}/payment', [BookingController::class, 'processPayment'])->name('booking.payment.process');
    Route::get('/booking/{id}/seats', [BookingController::class, 'selectSeats'])->name('booking.seats');
    Route::get('/booking/{id}/checkin', [BookingController::class, 'checkIn'])->name('booking.checkin');
    Route::post('/booking/{id}/baggage', [BookingController::class, 'addBaggage'])->name('booking.baggage');
    Route::get('/booking/{id}/boarding-pass', [BookingController::class, 'boardingPass'])->name('booking.boarding-pass');

    // Admin Routes (Optional - add middleware for admin role if needed)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/bookings', [AdminController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{id}', [AdminController::class, 'show'])->name('bookings.show');
        Route::delete('/bookings/{id}', [AdminController::class, 'destroy'])->name('bookings.destroy');
        Route::post('/flights/{id}/update-status', [AdminController::class, 'updateFlightStatus'])->name('flights.update-status');
    });
});
