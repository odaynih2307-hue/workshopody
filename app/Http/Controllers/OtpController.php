<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the OTP verification form
     */
    public function showForm()
    {
        // Check if OTP session exists
        if (!session()->has('login_otp')) {
            return redirect()->route('home');
        }

        return view('auth.otp');
    }

    /**
     * Verify the OTP
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6'
        ]);

        $storedOtp = session()->get('login_otp');

        if ((int) $request->otp === (int) $storedOtp) {
            // OTP is correct, clear the session
            session()->forget('login_otp');
            session()->save();

            return redirect()->route('home')->with('success', 'OTP verified successfully!');
        }

        return back()->with('error', 'Invalid OTP. Please try again.');
    }
}
