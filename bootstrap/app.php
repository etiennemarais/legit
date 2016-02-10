<?php

require_once __DIR__.'/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

$app->withEloquent();
$app->withFacades();

$app->singleton(Illuminate\Contracts\Debug\ExceptionHandler::class, App\Exceptions\Handler::class);
$app->singleton(Illuminate\Contracts\Console\Kernel::class, App\Console\Kernel::class);

# Middleware
 $app->routeMiddleware([
     'auth' => App\Http\Middleware\Authenticate::class,
 ]);

// $app->register(App\Providers\EventServiceProvider::class);
//if (env('APP_ENV') === 'local') {
//    $app->register(\OutlineLaravel\OutlineLaravelServiceProvider::class);
//}

# Service Providers
$app->register(App\Providers\AppServiceProvider::class);
$app->register(\Barryvdh\Cors\LumenServiceProvider::class);
$app->configure('cors');
$app->configure('app');

# Routes
$app->group(['namespace' => 'App\Http\Controllers'], function ($app) {
    require __DIR__.'/../app/Http/routes.php';
});

return $app;
