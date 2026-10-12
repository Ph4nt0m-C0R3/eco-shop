@extends('admin.auth.mfa.layout')
@section('heading', __('auth-security.mfa_security'))
@section('content')
    <p>{{ __('auth-security.mfa_enabled') }}</p>
    @if(session('recovery_codes'))
        <h2 class="h5">{{ __('auth-security.save_codes') }}</h2>
        <p>{{ __('auth-security.codes_once') }}</p>
        <ul class="list-unstyled">@foreach(session('recovery_codes') as $code)<li class="mb-2"><code class="mfa-key">{{ $code }}</code></li>@endforeach</ul>
    @endif
    <p>{{ __('auth-security.codes_remaining', ['count' => count(auth()->user()->two_factor_recovery_codes ?? [])]) }}</p>
    <a class="btn btn-outline-success mb-3" href="{{ route('adminDashboard') }}">{{ __('auth-security.dashboard') }}</a>
    <a class="btn btn-outline-success mb-3" href="{{ route('admin.mfa.setup') }}">{{ __('auth-security.replace_authenticator') }}</a>
    <hr>
    <h2 class="h5">{{ __('auth-security.replace_codes') }}</h2>
    <p class="small">{{ __('auth-security.replace_codes_hint') }}</p>
    <form method="POST" action="{{ route('admin.mfa.regenerate') }}">
        @csrf
        <label class="form-label" for="mfa-password">{{ __('auth-security.current_password') }}</label>
        <input id="mfa-password" class="form-control mb-3" name="password" type="password" required autocomplete="current-password" maxlength="128">
        <label class="form-label" for="mfa-code">{{ __('auth-security.authenticator_code') }}</label>
        <input id="mfa-code" class="form-control mb-3" name="code" type="text" required inputmode="numeric" pattern="[0-9]{6}" maxlength="6" autocomplete="one-time-code">
        <button class="btn btn-success w-100" type="submit">{{ __('auth-security.replace_codes') }}</button>
    </form>
@endsection
