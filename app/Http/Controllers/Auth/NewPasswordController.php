<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\PasswordRecovery;
use App\Support\PasswordPolicy;
use Illuminate\Http\Request;

class NewPasswordController extends Controller
{
    public function create(Request $request) { return view('auth.reset-password', ['request' => $request]); }
    public function store(Request $request, PasswordRecovery $recovery)
    {
        $request->validate(['token' => ['required', 'string', 'max:255'], 'email' => ['required', 'email', 'max:255'], 'password' => PasswordPolicy::rules()]);
        if (!$recovery->reset($request, false)) return back()->withInput($request->only('email'))->withErrors(['email' => __('auth-security.reset_invalid')]);
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('status', __('auth-security.password_updated'));
    }
}
