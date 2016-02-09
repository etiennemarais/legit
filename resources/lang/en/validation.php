<?php
return [
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => 'The :attribute must be at least :min characters.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'required' => 'The :attribute field is required.',
    'custom' => [
        'phone_number' => [
            'region' => 'The phone number is not the correct format for :region',
        ],
    ],
];
