<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\PasswordRecovery;
use Illuminate\Http\Request;

class PasswordResetLinkController extends Controller
{
    public function create() { return view('auth.forgot-password'); }
    public function store(Request $request, PasswordRecovery $recovery)
    {
        $request->validate(['email' => ['required', 'email', 'max:255']]);
        $recovery->send($request, false);
        return back()->with('status', __('auth-security.reset_requested'));
    }
}
