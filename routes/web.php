<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\ProfileController;

// Home
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth Routes
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);
Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);
Route::post('/logout', [SocialiteController::class, 'logout'])->name('logout');

if (config('payment.provider') === 'midtrans') {
    Route::post('/payments/webhooks/midtrans', [PaymentWebhookController::class, 'handleMidtrans'])
        ->middleware('throttle:midtrans-webhook')
        ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class])
        ->name('payments.webhook.midtrans');
}

// Protected Routes
// Guest Accessible Booking Flow
Route::get('/flights', [FlightController::class, 'index'])->name('flights.index');
Route::get('/flights/{id}', [FlightController::class, 'show'])->name('flights.show');
Route::get('/experience', [FlightController::class, 'experience'])->name('experience');
Route::get('/destinations', [FlightController::class, 'destinations'])->name('destinations');
Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
Route::get('/booking/{id}/payment', [BookingController::class, 'payment'])->name('booking.payment');
Route::get('/booking/{id}/seats', [BookingController::class, 'selectSeats'])->name('booking.seats');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Authenticated Booking Actions
    Route::delete('/booking/{id}', [BookingController::class, 'cancel'])->name('booking.cancel');
    Route::post('/booking/{id}/refund', [BookingController::class, 'refund'])->name('booking.refund');
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
