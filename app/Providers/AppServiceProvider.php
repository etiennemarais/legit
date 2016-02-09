<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Infrastructure\TenantScope\TenantScope;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Validator::extend('region', 'Legit\Validation\Phone@validateRegion');
        Validator::replacer('region', function($message, $attribute, $rule, $parameters) {
            return str_replace(':region', Config::get('country_iso'), $message);
        });
    }

    public function register()
    {
        if (env('APP_ENV') === 'local') {
            DB::enableQueryLog();
        }

        $this->app->singleton('Infrastructure\TenantScope\TenantScope', function ($app) {
            return new TenantScope();
        });
    }
}
