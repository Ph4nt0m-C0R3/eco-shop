<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('auth-security.mfa_title') }} | {{ config('app.brand') }}</title>
    <link href="{{ asset('vendor/ecoshop/f7456a80eeff00.css') }}" rel="stylesheet">
    <link href="{{ asset('global/css/eco-common.css') }}" rel="stylesheet">
    <style>body{background:#f5f8f1;color:#253b24}.mfa-card{max-width:620px;margin:auto;border:1px solid #dce6d4;border-radius:20px;background:white;padding:clamp(20px,5vw,40px);box-shadow:0 12px 36px #253b2410}.mfa-key{display:block;overflow-wrap:anywhere;word-break:break-all;background:#f2f6ed;padding:12px;border-radius:10px;color:#253b24}.mfa-card .btn-success{background:#41792d;border-color:#41792d}.mfa-card label{font-weight:600}</style>
</head>
<body>
<main class="container py-5">
    <section class="mfa-card" aria-labelledby="mfa-title">
        <p class="text-success fw-bold">{{ config('app.brand') }} · {{ __('auth-security.admin_access') }}</p>
        <h1 id="mfa-title" class="h3 mb-3">@yield('heading')</h1>
        @if(session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger" role="alert">@foreach($errors->all() as $error)<p class="mb-1">{{ $error }}</p>@endforeach</div>@endif
        @yield('content')
        <hr>
        <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="btn btn-outline-secondary" type="submit">{{ __('auth-security.sign_out') }}</button></form>
    </section>
</main>
</body>
</html>
