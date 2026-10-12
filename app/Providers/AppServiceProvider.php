<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Laravel\Socialite\Facades\Socialite::extend('github', fn ($app) =>
            \Laravel\Socialite\Facades\Socialite::buildProvider(\App\Auth\VerifiedGithubProvider::class, $app['config']['services.github']));
        \App\Models\User::observe(\App\Observers\UserSecurityObserver::class);
        \Illuminate\Support\Facades\RateLimiter::for('recovery', fn ($request) => [
            \Illuminate\Cache\RateLimiting\Limit::perMinute(10)->by('recovery-ip:'.$request->ip()),
            \Illuminate\Cache\RateLimiting\Limit::perMinute(3)->by('recovery-email:'.hash('sha256', strtolower((string) $request->input('email')))),
        ]);
        \Illuminate\Support\Facades\RateLimiter::for('security', fn ($request) =>
            $request->isMethodSafe() || $request->routeIs('admin.logout') ? \Illuminate\Cache\RateLimiting\Limit::none()
                : \Illuminate\Cache\RateLimiting\Limit::perMinute(20)->by('security:'.($request->user()?->id ?? $request->ip())));
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            if (app()->bound('request') && request()->hasSession()) {
                \App\Support\AccountSecurity::bind(request(), $event->user);
            }
        });
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        View::composer('*', function ($view) {
            $paymentIcons = PaymentMethod::where('is_active', true)
                ->whereNotNull('icon')
                ->get();

            $view->with('footerPaymentMethods', $paymentIcons);
        });
    }
}
