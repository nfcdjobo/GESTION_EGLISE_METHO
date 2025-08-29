<?php

namespace App\Providers;

use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Observers\SubscriptionObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\SubscriptionPaymentObserver;

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
        Subscription::observe(SubscriptionObserver::class);
        SubscriptionPayment::observe(SubscriptionPaymentObserver::class);
    }
}



