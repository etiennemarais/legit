<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Infrastructure\TenantScope\TenantScope;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Infrastructure\TenantScope\TenantScope', function ($app) {
            return new TenantScope();
        });

        // TODO Register custom validation here
        // public function extend($rule, $extension, $message = null)
    }
}
