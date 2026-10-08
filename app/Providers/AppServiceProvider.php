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
        View::composer('*', function ($view) {
            $paymentIcons = PaymentMethod::where('is_active', true)
                ->whereNotNull('icon')
                ->get();

            $view->with('footerPaymentMethods', $paymentIcons);
        });
    }
}
