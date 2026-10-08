<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Only USER accounts allowed
        $user = User::where('email', $request->email)
                    ->where('role', 'user')
                    ->first();

        // Admin / Superadmin / Non-user → explicit error
        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Invalid email address.'
                ]);
        }

        // Send USER verification/reset email
        $status = Password::broker('users')->sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', 'Verification email sent.')
            : back()->withErrors(['email' => 'Unable to send email. Try again later.']);
    }

}
