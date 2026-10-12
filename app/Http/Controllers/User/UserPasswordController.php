<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Auth\PasswordChallengeController;

class UserPasswordController extends PasswordChallengeController
{
    protected string $portal = 'user';
    protected string $views = 'user.profile.otp';
    protected string $profileRoute = 'user.profile';
}
