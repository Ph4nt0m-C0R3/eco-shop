<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class SocialLoginController extends Controller
{
    // redirect
    public function redirect($provider)
    {
        /** @var \Laravel\Socialite\Two\AbstractProvider $socialite */
        $socialite = Socialite::driver($provider);

        return $socialite->stateless()->redirect();
    }

    // callback
    public function callback($provider)
    {
        /** @var \Laravel\Socialite\Two\AbstractProvider $socialite */
        $socialite = Socialite::driver($provider);

        $socialUser = $socialite->stateless()->user();

        if (!$socialUser->email) {
            abort(403, 'No email returned from provider');
        }

        $user = User::where('email', $socialUser->email)->first();

        /**
         * CASE 1:
         * Email exists & is LOCAL account → BLOCK social login
         */
        if ($user && $user->provider === 'local') {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'This email is registered with password login. Please sign in using email and password.',
                ]);
        }

        /**
         * CASE 2:
         * Email exists & is SAME social provider → allow login
         */
        if ($user && $user->provider === $provider) {
            Auth::login($user);
            return to_route('userHome');
        }

        /**
         * CASE 3:
         * Email exists but different social provider → OPTIONAL decision
         * (You can block or allow — I’ll block for safety)
         */
        if ($user && $user->provider !== $provider) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'This email is already registered using '.$user->provider.'.',
                ]);
        }

        /**
         * CASE 4:
         * Email does not exist → create social account
         */
        $user = User::create([
            'name' => $socialUser->name ?? $socialUser->nickname ?? 'User',
            'email' => $socialUser->email,
            'email_verified_at' => now(),
            'role' => 'user',
            'provider' => $provider,
            'provider_id' => $socialUser->id,
            'profile' => $socialUser->avatar ?? null,
        ]);

        Auth::login($user);
        return to_route('userHome');

    }
}
