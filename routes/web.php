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
Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('login');
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);
Route::post('/logout', [SocialiteController::class, 'logout'])->name('logout');

// Protected Routes
// Guest Accessible Booking Flow
Route::get('/flights', [FlightController::class, 'index'])->name('flights.index');
Route::get('/flights/{id}', [FlightController::class, 'show'])->name('flights.show');
Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
Route::get('/booking/{id}/payment', [BookingController::class, 'payment'])->name('booking.payment');
Route::get('/booking/{id}/seats', [BookingController::class, 'selectSeats'])->name('booking.seats');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Authenticated Booking Actions
    Route::delete('/booking/{id}', [BookingController::class, 'cancel'])->name('booking.cancel');
    Route::post('/booking/{id}/payment', [BookingController::class, 'processPayment'])->name('booking.payment.process');
    Route::get('/booking/{id}/checkin', [BookingController::class, 'checkIn'])->name('booking.checkin');
    Route::post('/booking/{id}/baggage', [BookingController::class, 'addBaggage'])->name('booking.baggage');
    Route::get('/booking/{id}/boarding-pass', [BookingController::class, 'boardingPass'])->name('booking.boarding-pass');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/bookings', [AdminController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{id}', [AdminController::class, 'show'])->name('bookings.show');
        Route::delete('/bookings/{id}', [AdminController::class, 'destroy'])->name('bookings.destroy');
        Route::post('/flights/{id}/update-status', [AdminController::class, 'updateFlightStatus'])->name('flights.update-status');
    });
});
