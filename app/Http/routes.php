<?php

$app->group(['prefix' => 'api/v1', 'middleware' => 'auth'], function () use ($app) {
    $app->get('/', ['uses' => 'App\Http\Controllers\ApiController@index']);
    $app->get('/verification/check', [
        'uses' => 'App\Http\Controllers\Verification\VerificationController@check']
    );
});

# Catch all
$app->get('/', ['uses' => 'ApiController@welcome']);
