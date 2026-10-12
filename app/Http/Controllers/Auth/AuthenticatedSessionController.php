<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user();

        if ($user->isAdmin()) return to_route($user->two_factor_confirmed_at ? 'admin.mfa.challenge' : 'admin.mfa.setup');

        $redirectRoutes = [
            'superadmin' => 'adminDashboard',
            'admin'      => 'adminDashboard',
            'user'       => 'userHome',
        ];

        return to_route(
            $redirectRoutes[$user->role] ?? 'home'
        );
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            Auth::user()->update([
                'last_online_at' => Carbon::now(),
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $request->is('admin/*') ? redirect()->route('admin.login') : redirect('/');
    }
}
