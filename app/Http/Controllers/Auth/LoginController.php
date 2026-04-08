<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirect setelah login
     */
    protected $redirectTo = '/otp';

    /**
     * Override setelah login berhasil
     */
    protected function authenticated($request, $user)
    {
        // Generate OTP 6 karakter
        $otp = strtoupper(Str::random(6));

        $user->update([
            'otp' => $otp
        ]);

        // Kirim OTP ke email
        Mail::raw("Kode OTP login Anda adalah: $otp", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Kode OTP Login');
        });

        return redirect('/otp');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}