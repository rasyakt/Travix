<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                    'password' => bcrypt(Str::random(24)), // Random password for OAuth users
                ]
            );

            Auth::login($user, true);

            // Link guest bookings to the logged-in user
            $guestBookingIds = session()->get('guest_booking_ids', []);
            if (!empty($guestBookingIds)) {
                \App\Models\Booking::whereIn('id', $guestBookingIds)
                    ->whereNull('user_id')
                    ->update(['user_id' => $user->id]);

                session()->forget('guest_booking_ids');

                // If only one booking was made as guest, redirect back to its payment page
                if (count($guestBookingIds) === 1) {
                    return redirect()->route('booking.payment', $guestBookingIds[0])
                        ->with('success', 'Logged in successfully. You can now proceed with your payment.');
                }
            }

            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Failed to authenticate with Google. Please try again.');
        }
    }

    /**
     * Logout user
     */
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }
}
