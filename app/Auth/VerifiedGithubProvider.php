<?php

namespace App\Auth;

use GuzzleHttp\RequestOptions;
use Laravel\Socialite\Two\GithubProvider;

class VerifiedGithubProvider extends GithubProvider
{
    private bool $verifiedEmail = false;

    protected function getUserByToken($token)
    {
        $this->verifiedEmail = false;
        $user = parent::getUserByToken($token);
        $user['email_verified'] = $this->verifiedEmail;
        return $user;
    }

    protected function getEmailByToken($token)
    {
        $response = $this->getHttpClient()->get('https://api.github.com/user/emails', [
            RequestOptions::HEADERS => [
                'Accept' => 'application/vnd.github+json',
                'Authorization' => 'Bearer '.$token,
            ],
        ]);
        foreach (json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR) as $email) {
            if (($email['primary'] ?? false) === true && ($email['verified'] ?? false) === true) {
                $this->verifiedEmail = true;
                return $email['email'];
            }
        }
        return null;
    }
}
