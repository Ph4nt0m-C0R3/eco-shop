<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
if (!$app->configurationIsCached() || !$app->routesAreCached()) {
    fwrite(STDERR, "Cache configuration and routes before running this check.\n");
    exit(1);
}
foreach (['login', 'shop.index', 'product.show', 'stripe.checkout', 'adminDashboard', 'lang.switch'] as $name) {
    if (!$app['router']->getRoutes()->getByName($name)) {
        fwrite(STDERR, "Missing cached route: {$name}\n");
        exit(1);
    }
}
if (config('app.brand') !== 'EcoShop v2.0' || config('session.serialization') !== 'json') {
    fwrite(STDERR, "Cached application/session configuration is incomplete.\n");
    exit(1);
}
echo "Cached bootstrap passed: application configuration and critical routes load.\n";
