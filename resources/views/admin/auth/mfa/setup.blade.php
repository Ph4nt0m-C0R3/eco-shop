@extends('admin.auth.mfa.layout')
@section('heading', __('auth-security.mfa_setup'))
@section('content')
    <p>{{ __('auth-security.setup_instructions') }}</p>
    <dl><dt>{{ __('auth-security.account') }}</dt><dd>{{ auth()->user()->email }}</dd><dt>{{ __('auth-security.setup_key') }}</dt><dd><code class="mfa-key">{{ $secret }}</code></dd></dl>
    <p class="small text-muted">{{ __('auth-security.setup_expiry') }}</p>
    <form method="POST" action="{{ route('admin.mfa.enroll') }}">
        @csrf
        <label class="form-label" for="mfa-password">{{ __('auth-security.current_password') }}</label>
        <input id="mfa-password" class="form-control mb-3" name="password" type="password" required autocomplete="current-password" maxlength="128">
        <label class="form-label" for="mfa-code">{{ __('auth-security.authenticator_code') }}</label>
        <input id="mfa-code" class="form-control mb-3" name="code" type="text" required inputmode="numeric" pattern="[0-9]{6}" maxlength="6" autocomplete="one-time-code">
        <button class="btn btn-success w-100" type="submit">{{ __('auth-security.confirm_authenticator') }}</button>
    </form>
@endsection
