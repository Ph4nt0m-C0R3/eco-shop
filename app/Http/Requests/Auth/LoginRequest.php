<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return ['email' => ['required', 'string', 'email', 'max:255'], 'password' => ['required', 'string', 'max:128']];
    }
    public function authenticate(): void
    {
        $email = strtolower(trim($this->input('email')));
        $keys = ['login-pair:'.hash('sha256', $email.'|'.$this->ip()) => [5, 60],
            'login-account:'.hash('sha256', $email) => [20, 300], 'login-ip:'.$this->ip() => [100, 300]];
        foreach ($keys as $key => [$limit]) {
            if (RateLimiter::tooManyAttempts($key, $limit)) throw ValidationException::withMessages(['email' => __('auth.throttle', ['seconds' => RateLimiter::availableIn($key), 'minutes' => ceil(RateLimiter::availableIn($key) / 60)])]);
        }
        foreach ($keys as $key => [, $seconds]) RateLimiter::hit($key, $seconds);
        $user = User::where('email', $email)->first();
        $valid = Hash::check($this->input('password'), $user?->password ?: config('auth.login_dummy_hash'));
        $admin = $this->is('admin/*');
        if (!$valid || !$user || $user->provider !== 'local' || ($admin ? !$user->isAdmin() : $user->role !== 'user')
            || !Auth::attempt(['email' => $email, 'password' => $this->input('password'), 'role' => $user->role, 'provider' => 'local'], !$admin && $this->boolean('remember'))) {
            throw ValidationException::withMessages(['email' => __('auth.failed')]);
        }
        RateLimiter::clear(array_key_first($keys));
        $this->session()->put('auth.password_confirmed_at', now()->timestamp);
    }
}
