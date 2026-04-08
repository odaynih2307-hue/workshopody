<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // Debug request parameters
            \Log::info('Google callback received', [
                'code' => request()->input('code'),
                'state' => request()->input('state'),
                'url' => request()->fullUrl(),
            ]);

            // Try WITHOUT stateless first (uses session for state)
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'id_google' => $googleUser->getId(),
                ]
            );

            // 🔥 Generate OTP 6 digit angka
            $otp = random_int(100000, 999999);

            // 🔥 Simpan ke SESSION
            session()->put('login_otp', $otp);
            session()->save();

            // 🔥 Kirim OTP ke email
            Mail::raw("Kode OTP login Anda adalah: $otp", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Kode OTP Login');
            });

            Auth::login($user);

            return redirect()->route('otp.form');

        } catch (\Exception $e) {
            \Log::error('Google OAuth Error', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_code' => request()->input('code'),
                'request_state' => request()->input('state'),
            ]);
            return redirect('/')->with('error', 'Google login failed: ' . $e->getMessage());
        }
    }
}