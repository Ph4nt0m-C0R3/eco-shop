<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        try { $request->user()->sendEmailVerificationNotification(); }
        catch (\Throwable $exception) {
            \Illuminate\Support\Facades\Log::warning('Email verification delivery failed.', ['exception_type' => $exception::class]);
            throw \Illuminate\Validation\ValidationException::withMessages(['email' => __('auth-security.delivery_failed')]);
        }

        return back()->with('status', 'verification-link-sent');
    }
}
