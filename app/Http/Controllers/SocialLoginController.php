<?php

namespace App\Http\Controllers;

use App\Services\SocialAccounts;
use App\Support\OAuthConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function redirect(Request $request, string $provider)
    {
        $request->session()->forget(['oauth_context', 'state', 'code_verifier']);
        if (!OAuthConfiguration::ready($provider)) return $this->failure('oauth_unavailable');
        $origin = rtrim((string) config('app.url'), '/');
        // Start on the callback host: localhost and 127.0.0.1 have different cookies.
        if ($request->getSchemeAndHttpHost() !== $origin) return redirect($origin."/auth/{$provider}/redirect");
        $response = $this->driver($provider)->redirect();
        $request->session()->put('oauth_context', [
            'provider' => $provider, 'started_at' => now()->timestamp,
            'callback' => config("services.{$provider}.redirect"),
        ]);
        return $response;
    }

    public function callback(Request $request, string $provider, SocialAccounts $accounts)
    {
        $context = $request->session()->pull('oauth_context');
        $state = $request->session()->get('state');
        $received = $request->query('state');
        $valid = OAuthConfiguration::ready($provider) && is_array($context)
            && ($context['provider'] ?? null) === $provider
            && ($context['callback'] ?? null) === config("services.{$provider}.redirect")
            && is_int($context['started_at'] ?? null) && $context['started_at'] <= now()->timestamp
            && $context['started_at'] > now()->subMinutes(10)->timestamp
            && $request->getSchemeAndHttpHost() === rtrim((string) config('app.url'), '/')
            && is_string($state) && $state !== '' && is_string($received) && hash_equals($state, $received)
            && is_string($request->query('code')) && $request->query('code') !== ''
            && !$request->has('error');
        if (!$valid) {
            $request->session()->forget(['state', 'code_verifier']);
            return $this->failure('oauth_failed');
        }
        try {
            // Keep Socialite's own session-state verification enabled.
            $identity = $this->driver($provider)->user();
            $user = $accounts->resolve($provider, $identity);
            $request->session()->regenerate(true);
            Auth::guard('web')->login($user, false);
            return redirect()->route('userHome');
        } catch (ValidationException $exception) {
            return redirect()->route('login')->withErrors($exception->errors());
        } catch (\Throwable $exception) {
            // Provider exceptions may contain authorization codes/tokens in URLs or bodies.
            Log::warning('OAuth sign-in failed.', ['provider' => $provider, 'exception_type' => $exception::class]);
            return $this->failure('oauth_failed');
        } finally {
            $request->session()->forget(['state', 'code_verifier']);
        }
    }

    private function driver(string $provider)
    {
        return Socialite::driver($provider)->setScopes($provider === 'google' ? ['openid', 'profile', 'email'] : ['user:email']);
    }

    private function failure(string $key)
    {
        return redirect()->route('login')->withErrors(['email' => __('auth-security.'.$key)]);
    }
}
