<?php

require __DIR__.'/../../vendor/autoload.php';
$app = require __DIR__.'/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
if (config('app.env') !== 'testing' || config('database.default') !== 'mysql'
    || config('database.connections.mysql.database') !== 'ecoshop_security_test') exit(3);
[$script, $mode, $userId, $challengeId, $barrier, $marker, $input] = $argv;
$user = App\Models\User::findOrFail($userId);
$request = Illuminate\Http\Request::create('/security-concurrency', 'POST');
$store = new Illuminate\Session\Store('security-concurrency', new Illuminate\Session\ArraySessionHandler(120), str_repeat('c', 40), 'json');
$store->put('password_challenge', (int) $challengeId);
$store->put('password_grant', $input);
$request->setLaravelSession($store);
$request->setUserResolver(fn () => $user);
file_put_contents($barrier.'/'.$marker.'.ready', 'ready');
$deadline = microtime(true) + 10;
while (!is_file($barrier.'/go') && microtime(true) < $deadline) usleep(10000);
if (!is_file($barrier.'/go')) exit(4);
try {
    if ($mode === 'attempts') {
        for ($i = 0; $i < 5; $i++) {
            try { app(App\Services\PasswordChallenges::class)->verify($request, 'incorrect'); }
            catch (Illuminate\Validation\ValidationException) {}
        }
    } elseif ($mode === 'reset') {
        app(App\Services\PasswordChallenges::class)->reset($request, 'a concurrent reset passphrase');
    } elseif ($mode === 'mfa') {
        app(App\Services\AdminMfa::class)->challenge($request, $input, '');
    } else exit(5);
    echo 'succeeded';
} catch (Illuminate\Validation\ValidationException|Symfony\Component\HttpKernel\Exception\HttpException) {
    echo 'rejected';
}
