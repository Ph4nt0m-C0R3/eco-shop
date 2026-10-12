<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('model:prune')->everyMinute();


Artisan::command('orders:expire-reservations', function () {
    \App\Models\Order::where('payment_method', 'stripe')
        ->where('status', 'pending_payment')->where('payment_status', '!=', 'paid')
        ->whereNotNull('reservation_expires_at')->where('reservation_expires_at', '<=', now())
        ->chunkById(100, function ($orders) {
            foreach ($orders as $order) {
                try {
                    app(\App\Services\StripePayments::class)->expire($order);
                } catch (\Throwable $e) {
                    report($e); // Retry on the next schedule; keep stock reserved when provider is unavailable.
                }
            }
        });
    $this->info('Expired payment reservations checked.');
})->purpose('Release abandoned Stripe reservations after checking the provider');
Schedule::command('orders:expire-reservations')->everyMinute()->withoutOverlapping();


Artisan::command('payments:privatize-proofs', function () {
    $private = \Illuminate\Support\Facades\Storage::disk('local');
    $public = \Illuminate\Support\Facades\Storage::disk('public');
    \App\Models\Order::whereNotNull('payment_screenshot')->chunkById(100, function ($orders) use ($private, $public) {
        foreach ($orders as $order) {
            $path = $order->payment_screenshot;
            if (!str_starts_with($path, 'payment_screenshots/') || str_contains($path, '..')) continue;
            if ($public->exists($path)) {
                $bytes = $public->get($path);
                if (!$private->put($path, $bytes) || $private->get($path) !== $bytes) {
                    throw new \RuntimeException('Payment proof could not be copied safely.');
                }
                $public->delete($path);
            }
            // Windows installations may have a physical public/storage copy instead of a link.
            $copy = public_path('storage/'.$path);
            $root = realpath(public_path('storage'));
            $resolved = realpath($copy);
            if ($private->exists($path) && $root && $resolved && str_starts_with($resolved, $root.DIRECTORY_SEPARATOR)) {
                if (file_get_contents($resolved) !== $private->get($path)) {
                    throw new \RuntimeException('Public proof differs from the private copy.');
                }
                \Illuminate\Support\Facades\File::delete($resolved);
            }
        }
    });
    $this->info('Legacy payment proofs moved to private storage.');
})->purpose('Move legacy public payment proofs to authenticated private storage');
