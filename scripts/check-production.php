<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$problems = App\Support\ProductionReadiness::problems(App\Support\ProductionReadiness::applicationSettings());
if (PHP_VERSION_ID < 80500) $problems[] = 'PHP 8.5 or newer is required.';
foreach (['curl', 'dom', 'fileinfo', 'mbstring', 'openssl', 'pdo_mysql', 'zip'] as $extension) {
    if (!extension_loaded($extension)) $problems[] = "Required PHP extension missing: {$extension}.";
}
if ($problems) {
    foreach ($problems as $problem) fwrite(STDERR, '- '.$problem.PHP_EOL);
    exit(1);
}
echo "Production configuration checks passed. Verify the live TLS/proxy, database grants, worker services and filesystem permissions on the target host.\n";
