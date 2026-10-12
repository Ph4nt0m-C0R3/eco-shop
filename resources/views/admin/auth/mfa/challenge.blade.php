@extends('admin.auth.mfa.layout')
@section('heading', __('auth-security.mfa_title'))
@section('content')
    <p>{{ __('auth-security.challenge_instructions') }}</p>
    <form method="POST" action="{{ route('admin.mfa.verify') }}">
        @csrf
        <label class="form-label" for="mfa-code">{{ __('auth-security.authenticator_code') }}</label>
        <input id="mfa-code" class="form-control mb-3" name="code" type="text" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" autocomplete="one-time-code" autofocus>
        <p class="text-muted">{{ __('auth-security.or_recovery') }}</p>
        <label class="form-label" for="recovery-code">{{ __('auth-security.recovery_code') }}</label>
        <input id="recovery-code" class="form-control mb-3" name="recovery_code" type="text" maxlength="64" autocomplete="off" spellcheck="false">
        <button class="btn btn-success w-100" type="submit">{{ __('auth-security.verify_sign_in') }}</button>
    </form>
@endsection
