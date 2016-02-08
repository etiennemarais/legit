<?php

$factory->define(Legit\Verification\Verification::class, function ($faker) {
    return [
        'client_user_id' => 1,
        'country_id' => 1,
        'phone_number' => $faker->phoneNumber,
        'verification_status' => 'unverified',
    ];
});

$factory->define(Legit\Countries\Country::class, function($faker) {
    return [
        'country_code' => 'olxug',
        'country_iso' => 'UG',
        'api_key' => 'apikeyuganda',
        'status' => 'enabled',
    ];
});
