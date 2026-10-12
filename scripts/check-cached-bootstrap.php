<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
if (!$app->configurationIsCached() || !$app->routesAreCached()) {
    fwrite(STDERR, "Cache configuration and routes before running this check.\n");
    exit(1);
}
foreach (['login', 'socialLogin', 'social.callback', 'shop.index', 'product.show', 'stripe.checkout', 'adminDashboard', 'lang.switch', 'admin.mfa.challenge', 'admin.mfa.enroll', 'admin.logout', 'user.password.otp.verify'] as $name) {
    if (!$app['router']->getRoutes()->getByName($name)) {
        fwrite(STDERR, "Missing cached route: {$name}\n");
        exit(1);
    }
}
if (config('app.brand') !== 'EcoShop v2.0' || config('session.serialization') !== 'json'
    || config('hashing.driver') !== 'argon2id' || config('auth.guards.admin.driver') !== 'session'
    || config('session.customer_cookie') === config('session.admin_cookie')) {
    fwrite(STDERR, "Cached application/session configuration is incomplete.\n");
    exit(1);
}
echo "Cached bootstrap passed: application configuration and critical routes load.\n";
