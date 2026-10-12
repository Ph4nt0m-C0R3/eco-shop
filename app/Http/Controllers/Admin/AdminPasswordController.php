<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Auth\PasswordChallengeController;
use App\Services\PasswordRecovery;
use App\Support\PasswordPolicy;
use Illuminate\Http\Request;

class AdminPasswordController extends PasswordChallengeController
{
    protected string $portal = 'admin';
    protected string $views = 'admin.auth.otp';
    protected string $profileRoute = 'admin#profile';
    public function forgotPassword() { return view('admin.auth.forgot-password'); }
    public function sendResetLink(Request $request, PasswordRecovery $recovery)
    {
        $request->validate(['email' => ['required', 'email', 'max:255']]);
        $recovery->send($request, true);
        return back()->with('success', __('auth-security.reset_requested'));
    }
    public function showResetForm(Request $request, string $token)
    {
        return view('admin.auth.reset-password', ['token' => $token, 'email' => $request->query('email')]);
    }
    public function resetWithToken(Request $request, PasswordRecovery $recovery)
    {
        $request->validate(['token' => ['required', 'string', 'max:255'], 'email' => ['required', 'email', 'max:255'], 'password' => PasswordPolicy::rules()]);
        if (!$recovery->reset($request, true)) return back()->withErrors(['email' => __('auth-security.reset_invalid')]);
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('success', __('auth-security.password_updated'));
    }
}
