<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\PasswordOtp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class AdminPasswordController extends Controller
{
    // ===== ADMIN PASSWORD UPDATE =====
    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:8|max:15',
            'password_confirmation' => 'required|min:8|same:password|max:15',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors([
                'old_password' => 'Old password is incorrect',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'password_updated_at' => Carbon::now(),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function forgotPassword()
    {
        return view('admin.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $admin = User::where('email', $request->email)
                ->whereIn('role', ['admin','superadmin'])
                ->first();

        if (!$admin) {
            return back()->withErrors(['email' => 'Invalid admin email.']);
        }

        $status = Password::broker('users')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Reset link sent.')
            : back()->withErrors(['email' => 'Unable to send email.']);
    }

    public function showResetForm(Request $request, string $token)
    {
        return view('admin.auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetWithToken(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|max:15',
            'password_confirmation' => 'required|min:8|same:password|max:15',
        ]);

        $status = Password::broker('users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($admin, $password) {
                $admin->forceFill([
                    'password' => Hash::make($password),
                    'password_updated_at' => Carbon::now(),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('admin.login')->with('success', 'Password reset successfully.')
            : back()->withErrors(['email' => 'Invalid or expired reset token.']);
    }

    public function showOtpVerifyForm()
    {
        abort_if(!session('otp_email'), 403);

        $otpRecord = PasswordOtp::where('email', session('otp_email'))->first();

        if (!$otpRecord) abort(403);

        if ($otpRecord->expires_at->isPast()) {
            $otpRecord->delete();

            return redirect()
                ->route('admin.password.otp.request')
                ->withErrors(['otp' => 'OTP expired. Request new one.']);
        }

        $remainingSeconds = (int) max(
            0,
            now()->diffInSeconds($otpRecord->expires_at, false)
        );

        return view('admin.auth.otp.verify', compact('remainingSeconds'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required',
        ]);

        $otpRecord = PasswordOtp::where('email', session('otp_email'))->first();

        if (!$otpRecord || $otpRecord->expires_at < now()) {
            PasswordOtp::where('email', session('otp_email'))->delete();
            return back()->withErrors(['otp' => 'OTP expired']);
        }

        if ($otpRecord->attempts >= 5) {
            PasswordOtp::where('email', session('otp_email'))->delete();
            return back()->withErrors([
                'otp' => 'Too many attempts. Request new OTP.'
            ]);
        }

        if ($otpRecord->otp !== $request->otp) {
            $otpRecord->increment('attempts');
            return back()->withErrors(['otp' => 'Invalid OTP']);
        }

        session(['otp_verified' => true]);

        return redirect()->route('admin.password.otp.reset.form');
    }

    public function sendOtp()
    {
        /** @var \App\Models\User $admin */
        $admin = Auth::user();

        $otp = rand(100000, 999999);

        PasswordOtp::updateOrCreate(
            ['email' => $admin->email],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(5),
                'attempts' => 0,
            ]
        );

        Mail::raw("Your Admin Password Reset OTP is: {$otp}", function ($mail) use ($admin) {
            $mail->to($admin->email)
                ->subject('Admin Password Reset OTP');
        });

        session([
            'otp_email' => $admin->email
        ]);

        return redirect()
            ->route('admin.password.otp.verify.form')
            ->with('success', 'OTP sent to your email.');
    }

    public function resetWithOtp(Request $request)
    {
        abort_if(!session('otp_verified'), 403);

        $request->validate([
            'password' => 'required|min:8|max:15',
            'password_confirmation' => 'required|same:password|min:8|max:15',
        ]);

        /** @var \App\Models\User $admin */
        $admin = Auth::user();

        $admin->update([
            'password' => Hash::make($request->password),
            'password_updated_at' => Carbon::now(),
        ]);

        PasswordOtp::where('email', $admin->email)->delete();

        session()->forget(['otp_email', 'otp_verified']);

        return redirect()
            ->route('admin#profile')
            ->with('success', 'Password updated successfully.');
    }

    public function showOtpRequestForm()
    {
        return view('admin.auth.otp.request');
    }

    public function showResetWithOtpForm()
    {
        abort_if(!session('otp_verified'), 403);
        return view('admin.auth.otp.reset');
    }

    public function resendOtp()
    {
        abort_if(!session('otp_email'), 403);

        $admin = Auth::user();

        $otp = rand(100000, 999999);

        PasswordOtp::updateOrCreate(
            ['email' => $admin->email],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(5),
                'attempts' => 0,
            ]
        );

        Mail::raw("Your Admin Password Reset OTP is: {$otp}", function ($mail) use ($admin) {
            $mail->to($admin->email)
                ->subject('Admin Password Reset OTP');
        });

        return response()->json([
            'success' => true,
            'remainingSeconds' => 300
        ]);
    }

}
