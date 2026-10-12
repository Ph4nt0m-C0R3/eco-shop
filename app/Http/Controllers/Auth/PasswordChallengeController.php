<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\PasswordChallenges;
use App\Support\AccountSecurity;
use App\Support\PasswordPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

abstract class PasswordChallengeController extends Controller
{
    protected string $portal;
    protected string $views;
    protected string $profileRoute;
    public function updatePassword(Request $request)
    {
        abort_unless($request->user()->provider === 'local', 403);
        $field = $this->portal === 'admin' ? 'old_password' : 'current_password';
        $request->validate([$field => ['required', 'string', 'max:128'], 'password' => PasswordPolicy::rules()]);
        $user = $request->user();
        if (!$user->password || !Hash::check($request->input($field), $user->password)) throw ValidationException::withMessages([$field => __('auth-security.current_password_invalid')]);
        $fresh = AccountSecurity::changePassword($user, $request->input('password'));
        AccountSecurity::keepCurrentSession($request, $fresh);
        return back()->with('success', __('auth-security.password_updated'));
    }
    public function showOtpRequestForm(Request $request)
    {
        abort_unless($request->user()->provider === 'local', 403);
        return view($this->views.'.request');
    }
    public function sendOtp(Request $request, PasswordChallenges $challenges)
    {
        $challenges->send($request);
        return redirect()->route($this->portal.'.password.otp.verify.form')->with('success', __('auth-security.code_sent'));
    }
    public function showOtpVerifyForm(Request $request, PasswordChallenges $challenges)
    {
        if (!$row = $challenges->current($request)) return redirect()->route($this->portal.'.password.otp.request')->withErrors(['otp' => __('auth-security.invalid_code')]);
        return view($this->views.'.verify', ['remainingSeconds' => (int) max(0, now()->diffInSeconds($row->expires_at, false))]);
    }
    public function verifyOtp(Request $request, PasswordChallenges $challenges)
    {
        $request->validate(['otp' => ['required', 'string', 'max:64']]);
        $challenges->verify($request, $request->input('otp'));
        return redirect()->route($this->portal.'.password.otp.reset.form');
    }
    public function showResetWithOtpForm(Request $request, PasswordChallenges $challenges)
    {
        abort_unless($challenges->current($request, true), 403);
        return view($this->views.'.reset');
    }
    public function resetWithOtp(Request $request, PasswordChallenges $challenges)
    {
        $request->validate(['password' => PasswordPolicy::rules()]);
        $fresh = $challenges->reset($request, $request->input('password'));
        AccountSecurity::keepCurrentSession($request, $fresh);
        return redirect()->route($this->profileRoute)->with('success', __('auth-security.password_updated'));
    }
    public function resendOtp(Request $request, PasswordChallenges $challenges)
    {
        abort_unless($request->session()->has('password_challenge'), 403);
        $challenges->send($request);
        return response()->json(['success' => true, 'remainingSeconds' => 300]);
    }
}
