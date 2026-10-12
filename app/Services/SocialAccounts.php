<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Two\User as ProviderUser;

class SocialAccounts
{
    public function resolve(string $provider, ProviderUser $identity): User
    {
        abort_unless(in_array($provider, \App\Support\OAuthConfiguration::PROVIDERS, true), 404);
        $id = (string) $identity->getId();
        $email = strtolower(trim((string) $identity->getEmail()));
        $raw = $identity->getRaw();
        if (!$id || strlen($id) > 255 || preg_match('/[\x00-\x20\x7f]/', $id)
            || strlen($email) > 255 || !filter_var($email, FILTER_VALIDATE_EMAIL)
            || !in_array($raw['email_verified'] ?? false, [true, 'true'], true)
            || !is_string($raw['email'] ?? null) || strtolower(trim($raw['email'])) !== $email) {
            throw ValidationException::withMessages(['email' => __('auth-security.oauth_verified_email')]);
        }

        return DB::transaction(function () use ($provider, $identity, $id, $email) {
            $user = User::where('provider', $provider)->where('provider_id', $id)->lockForUpdate()->first();
            if ($user) {
                if ($user->role !== 'user') $this->conflict();
                // Stable provider IDs own the identity; sign-in does not replace shop account details.
                return $user;
            }
            if (User::whereRaw('LOWER(email) = ?', [$email])->exists()) $this->conflict();
            $avatar = $identity->getAvatar();
            $avatarHost = is_string($avatar) ? strtolower((string) parse_url($avatar, PHP_URL_HOST)) : '';
            $trustedAvatar = is_string($avatar) && strlen($avatar) <= 255 && parse_url($avatar, PHP_URL_SCHEME) === 'https'
                && ($provider === 'github' ? $avatarHost === 'avatars.githubusercontent.com'
                    : $avatarHost === 'lh3.googleusercontent.com');
            return User::create([
                'name' => mb_substr((string) ($identity->getName() ?: $identity->getNickname() ?: 'Customer'), 0, 255),
                'email' => $email, 'email_verified_at' => now(), 'role' => 'user',
                'provider' => $provider, 'provider_id' => $id,
                'profile' => $trustedAvatar ? $avatar : null, 'locale' => app()->getLocale(),
            ]);
        }, 3);
    }

    private function conflict(): never
    {
        throw ValidationException::withMessages(['email' => __('auth-security.oauth_conflict')]);
    }
}
