<?php

namespace App\Support;

class OAuthConfiguration
{
    public const PROVIDERS = ['google', 'github'];

    public static function problems(string $provider): array
    {
        if (!in_array($provider, self::PROVIDERS, true)) return ['Unsupported sign-in provider.'];
        $problems = [];
        foreach (['client_id', 'client_secret'] as $key) {
            $value = (string) config("services.{$provider}.{$key}");
            if (!$value || str_contains($value, '__REPLACE_')) $problems[] = strtoupper($provider).' '.str_replace('_', ' ', $key).' is missing.';
        }
        $appUrl = rtrim((string) config('app.url'), '/');
        $callback = (string) config("services.{$provider}.redirect");
        $parts = parse_url($appUrl);
        if (!$parts || !isset($parts['scheme'], $parts['host'])
            || !in_array($parts['scheme'], ['http', 'https'], true)
            || isset($parts['user']) || isset($parts['pass'])
            || isset($parts['query']) || isset($parts['fragment']) || !empty($parts['path'])
            || ($parts['scheme'] !== 'https' && (config('app.env') === 'production'
                || !in_array($parts['host'], ['localhost', '127.0.0.1', '[::1]'], true)))
            || $callback !== $appUrl."/auth/{$provider}/callback") {
            $problems[] = strtoupper($provider).' callback must match APP_URL and its /auth/'.$provider.'/callback path; use HTTPS outside localhost.';
        }
        return $problems;
    }

    public static function ready(string $provider): bool
    {
        return self::problems($provider) === [];
    }
}
