<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Infrastructure\TenantScope\TenantScope;
use Legit\Sending\Clickatell\ClickatellSendingProvider;
use Legit\Sending\Contracts\SendingProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        app('validator')->extend('region', 'Legit\Validation\Phone@validateRegion');
        app('validator')->replacer('region', function($message, $attribute, $rule, $parameters) {
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

        $this->app->bind(SendingProvider::class, function() {
            switch (env('SMS_GATEWAY_DRIVER')) {
                case 'clickatell':
                default:
                    return new ClickatellSendingProvider(
                        env('SMS_GATEWAY_USER'),
                        env('SMS_GATEWAY_PASS'),
                        env('SMS_GATEWAY_API_ID')
                    );
                    break;
            }
        });
    }
}
