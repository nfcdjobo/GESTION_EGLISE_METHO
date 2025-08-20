<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\PermissionService;

class PermissionServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PermissionService::class, function ($app) {
            return new PermissionService();
        });
    }

    public function boot()
    {
        //
    }
}
