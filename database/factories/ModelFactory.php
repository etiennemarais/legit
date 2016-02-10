<?php

$factory->define(Legit\Verification\Verification::class, function ($faker) {
    return [
        'client_user_id' => 1,
        'country_id' => 1,
        'phone_number' => '27848118111',
        'verification_status' => 'unverified',
    ];
});

$factory->define(Legit\Countries\Country::class, function($faker) {
    return [
        'country_code' => 'olxza',
        'country_iso' => 'ZA',
        'api_key' => 'apikeysouthafrica',
        'status' => 'enabled',
    ];
});