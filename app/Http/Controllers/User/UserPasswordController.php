<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\PasswordOtp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserPasswordController extends Controller
{
    // ===== USER PASSWORD UPDATE (OLD PASSWORD) =====
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|max:15',
            'password_confirmation' => 'required|same:password|min:8|max:15',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
            ->withErrors(['current_password' => 'Current password is incorrect'])
            ->withInput();

        }

        $user->update([
            'password' => Hash::make($request->password),
            'password_updated_at' => Carbon::now(),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    // ===== SHOW OTP REQUEST =====
    public function showOtpRequestForm()
    {
        return view('user.profile.otp.request');
    }

    // ===== SEND OTP =====
    public function sendOtp()
    {
        $user = Auth::user();

        $otp = rand(100000, 999999);

        PasswordOtp::updateOrCreate(
['email' => $user->email],
    [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(5),
                'attempts' => 0,
            ]
        );

        Mail::raw("Your EcoShop password reset OTP is: {$otp}", function ($mail) use ($user) {
            $mail->to($user->email)
                ->subject('EcoShop Password Reset OTP');
        });

        session([
            'otp_email' => $user->email
        ]);

        return redirect()
            ->route('user.password.otp.verify.form')
            ->with('success', 'OTP sent to your email.');
    }

    // ===== SHOW OTP VERIFY =====
    public function showOtpVerifyForm()
    {
        abort_if(!session('otp_email'), 403);

        $otpRecord = PasswordOtp::where('email', session('otp_email'))->first();

        if (!$otpRecord) abort(403);

        if ($otpRecord->expires_at->isPast()) {
            $otpRecord->delete();

            return redirect()
                ->route('user.password.otp.request')
                ->withErrors(['otp' => 'OTP expired. Request a new one.']);
        }

        $remainingSeconds = (int) max(0, now()->diffInSeconds($otpRecord->expires_at, false));

        return view('user.profile.otp.verify', compact('remainingSeconds'));
    }

    // ===== VERIFY OTP =====
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required',
        ]);

        $otpRecord = PasswordOtp::where('email', session('otp_email'))->first();

        // No OTP or expired
        if (!$otpRecord || $otpRecord->expires_at < now()) {
            PasswordOtp::where('email', session('otp_email'))->delete();
            return back()->withErrors(['otp' => 'OTP expired']);
        }

        if ($otpRecord->attempts >= 5) {
            PasswordOtp::where('email', session('otp_email'))->delete();
            return back()->withErrors(['otp' => 'Too many attempts. Request new OTP.']);
        }

        if ($otpRecord->otp !== $request->otp) {
            $otpRecord->increment('attempts');
            return back()->withErrors(['otp' => 'Invalid OTP']);
        }

        session(['otp_verified' => true]);

        return redirect()->route('user.password.otp.reset.form');
    }

    // ===== SHOW RESET FORM =====
    public function showResetWithOtpForm()
    {
        abort_if(!session('otp_verified'), 403);
        return view('user.profile.otp.reset');
    }

    // ===== RESET PASSWORD (OTP) =====
    public function resetWithOtp(Request $request)
    {
        abort_if(!session('otp_verified'), 403);

        $request->validate([
            'password' => 'required|min:8|max:15',
            'password_confirmation' => 'required|min:8|same:password|max:15',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->update([
            'password' => Hash::make($request->password),
            'password_updated_at' => Carbon::now(),
        ]);

        PasswordOtp::where('email', $user->email)->delete();

        session()->forget(['otp_email', 'otp_verified']);

        return redirect()
            ->route('user.profile')
            ->with('success', 'Password updated successfully.');
    }

    public function resendOtp()
    {
        abort_if(!session('otp_email'), 403);

        $user = Auth::user();

        $otp = rand(100000, 999999);

        PasswordOtp::updateOrCreate(
            ['email' => $user->email],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(5),
                'attempts' => 0,
            ]
        );

        Mail::raw("Your EcoShop password reset OTP is: {$otp}", function ($mail) use ($user) {
            $mail->to($user->email)->subject('EcoShop Password Reset OTP');
        });

        return response()->json([
            'success' => true,
            'remainingSeconds' => 300
        ]);
    }
}
