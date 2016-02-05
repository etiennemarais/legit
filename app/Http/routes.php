<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->group(['prefix' => 'api/v1'/*, 'middleware' => 'auth'*/], function () use ($app) {
    $app->get('/', ['uses' => 'App\Http\Controllers\ApiController@index']);
});

# Catch all
$app->get('/', ['uses' => 'ApiController@welcome']);
