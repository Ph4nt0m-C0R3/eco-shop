<!DOCTYPE html>
<html lang="{{ app()->getLocale() === 'mm' ? 'my' : 'en' }}">
<head><meta charset="UTF-8"><title>{{ config('app.brand') }}</title></head>
<body style="font-family:Arial,sans-serif;line-height:1.6;color:#243d2b;padding:24px">
    <h1>{{ config('app.brand') }}</h1>
    <p>{{ __('auth-security.email_test_body') }}</p>
    <p>{{ __('auth-security.email_test_hint') }}</p>
</body>
</html>
