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
Route::post('/login', [LoginController::class, 'store']);
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
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Guest (not logged in)
    Route::get('/login', [App\Http\Controllers\Admin\Auth\AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\Auth\AdminLoginController::class, 'login']);

    // Admin Protected Routes
    Route::middleware('admin')->group(function () {
        // Logout
        Route::post('/logout', [App\Http\Controllers\Admin\Auth\AdminLoginController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Bookings
        Route::get('/bookings', [App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{id}', [App\Http\Controllers\Admin\BookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{id}/status', [App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('bookings.update-status');
        Route::delete('/bookings/{id}', [App\Http\Controllers\Admin\BookingController::class, 'destroy'])->name('bookings.destroy');

        // Flights
        Route::get('/flights', [App\Http\Controllers\Admin\FlightController::class, 'index'])->name('flights.index');
        Route::get('/flights/create', [App\Http\Controllers\Admin\FlightController::class, 'create'])->name('flights.create');
        Route::post('/flights', [App\Http\Controllers\Admin\FlightController::class, 'store'])->name('flights.store');
        Route::get('/flights/{id}', [App\Http\Controllers\Admin\FlightController::class, 'show'])->name('flights.show');
        Route::get('/flights/{id}/edit', [App\Http\Controllers\Admin\FlightController::class, 'edit'])->name('flights.edit');
        Route::put('/flights/{id}', [App\Http\Controllers\Admin\FlightController::class, 'update'])->name('flights.update');
        Route::post('/flights/{id}/update-status', [App\Http\Controllers\Admin\FlightController::class, 'updateStatus'])->name('flights.update-status');
        Route::post('/flights/{id}/update-prices', [App\Http\Controllers\Admin\FlightController::class, 'updatePrices'])->name('flights.update-prices');
        Route::delete('/flights/{id}', [App\Http\Controllers\Admin\FlightController::class, 'destroy'])->name('flights.destroy');

        // Users
        Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
        Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
        Route::get('/users/{id}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

        // Airlines
        Route::get('/airlines', [App\Http\Controllers\Admin\AirlineController::class, 'index'])->name('airlines.index');
        Route::get('/airlines/create', [App\Http\Controllers\Admin\AirlineController::class, 'create'])->name('airlines.create');
        Route::post('/airlines', [App\Http\Controllers\Admin\AirlineController::class, 'store'])->name('airlines.store');
        Route::get('/airlines/{id}/edit', [App\Http\Controllers\Admin\AirlineController::class, 'edit'])->name('airlines.edit');
        Route::put('/airlines/{id}', [App\Http\Controllers\Admin\AirlineController::class, 'update'])->name('airlines.update');
        Route::delete('/airlines/{id}', [App\Http\Controllers\Admin\AirlineController::class, 'destroy'])->name('airlines.destroy');

        // Airports
        Route::get('/airports', [App\Http\Controllers\Admin\AirportController::class, 'index'])->name('airports.index');
        Route::get('/airports/create', [App\Http\Controllers\Admin\AirportController::class, 'create'])->name('airports.create');
        Route::post('/airports', [App\Http\Controllers\Admin\AirportController::class, 'store'])->name('airports.store');
        Route::get('/airports/{id}/edit', [App\Http\Controllers\Admin\AirportController::class, 'edit'])->name('airports.edit');
        Route::put('/airports/{id}', [App\Http\Controllers\Admin\AirportController::class, 'update'])->name('airports.update');
        Route::delete('/airports/{id}', [App\Http\Controllers\Admin\AirportController::class, 'destroy'])->name('airports.destroy');

        // Schedules
        Route::get('/schedules', [App\Http\Controllers\Admin\ScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/schedules/create', [App\Http\Controllers\Admin\ScheduleController::class, 'create'])->name('schedules.create');
        Route::post('/schedules', [App\Http\Controllers\Admin\ScheduleController::class, 'store'])->name('schedules.store');
        Route::get('/schedules/{id}/edit', [App\Http\Controllers\Admin\ScheduleController::class, 'edit'])->name('schedules.edit');
        Route::put('/schedules/{id}', [App\Http\Controllers\Admin\ScheduleController::class, 'update'])->name('schedules.update');
        Route::delete('/schedules/{id}', [App\Http\Controllers\Admin\ScheduleController::class, 'destroy'])->name('schedules.destroy');

        // Payments
        Route::get('/payments', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/{id}', [App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('payments.show');
        Route::post('/payments/{id}/mark-success', [App\Http\Controllers\Admin\PaymentController::class, 'markSuccess'])->name('payments.mark-success');
        Route::post('/payments/{id}/mark-failed', [App\Http\Controllers\Admin\PaymentController::class, 'markFailed'])->name('payments.mark-failed');

        // Aircraft
        Route::get('/aircraft', [App\Http\Controllers\Admin\AircraftController::class, 'index'])->name('aircraft.index');
        Route::get('/aircraft/create', [App\Http\Controllers\Admin\AircraftController::class, 'create'])->name('aircraft.create');
        Route::post('/aircraft', [App\Http\Controllers\Admin\AircraftController::class, 'store'])->name('aircraft.store');
        Route::get('/aircraft/{id}/edit', [App\Http\Controllers\Admin\AircraftController::class, 'edit'])->name('aircraft.edit');
        Route::put('/aircraft/{id}', [App\Http\Controllers\Admin\AircraftController::class, 'update'])->name('aircraft.update');
        Route::delete('/aircraft/{id}', [App\Http\Controllers\Admin\AircraftController::class, 'destroy'])->name('aircraft.destroy');
        Route::get('/aircraft/{id}/instances', [App\Http\Controllers\Admin\AircraftController::class, 'instances'])->name('aircraft.instances');
        Route::post('/aircraft/{aircraftId}/instances', [App\Http\Controllers\Admin\AircraftController::class, 'storeInstance'])->name('aircraft.instances.store');
        Route::delete('/aircraft/{aircraftId}/instances/{instanceId}', [App\Http\Controllers\Admin\AircraftController::class, 'destroyInstance'])->name('aircraft.instances.destroy');
    });
});
