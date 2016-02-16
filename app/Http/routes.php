<?php

$app->group(['prefix' => 'api/v1', 'middleware' => 'auth'], function () use ($app) {
    $app->get('/', ['uses' => 'App\Http\Controllers\ApiController@index']);
    $app->get('/verification/check', [
        'uses' => 'App\Http\Controllers\Verification\VerificationController@check'
    ]);
    $app->post('/verification/verify', [
        'uses' => 'App\Http\Controllers\Verification\VerificationController@verify'
    ]);
    $app->post('/code/send', ['uses' => 'App\Http\Controllers\Code\CodeController@send']);
});

# Catch all
$app->get('/', ['uses' => 'ApiController@welcome', 'middleware' => 'auth']);
