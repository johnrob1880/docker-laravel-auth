<?php

return [

    'locations' => [
        'us' => [
            'country' => 'usa',
            'default_locale' => 'en',
            'username' => env('OMEGAQUANT_US_API_USERNAME', 'apiuser'),
            'password' => env('OMEGAQUANT_US_API_PASSWORD', 'apiuser'),
            'base_uri' => env('OMEGAQUANT_US_WEBAPI_BASE_URI', 'http://localhost:15300')
        ],
        'au' => [
            'country' => 'aus',
            'default_locale' => 'au',
            'username' => env('OMEGAQUANT_AU_API_USERNAME', 'apiuser'),
            'password' => env('OMEGAQUANT_AU_API_PASSWORD', 'apiuser'),
            'base_uri' => env('OMEGAQUANT_AU_WEBAPI_BASE_URI', 'http://localhost:15300')
        ],
        'au-en' => [
            'country' => 'aus',
            'default_locale' => 'en',
            'username' => env('OMEGAQUANT_AU_API_USERNAME', 'apiuser'),
            'password' => env('OMEGAQUANT_AU_API_PASSWORD', 'apiuser'),
            'base_uri' => env('OMEGAQUANT_AU_WEBAPI_BASE_URI', 'http://localhost:15300')
        ],
        'zh' => [
            'country' => 'china',
            'default_locale' => 'zh-CN',
            'username' => env('OMEGAQUANT_ZH_API_USERNAME', 'apiuser'),
            'password' => env('OMEGAQUANT_ZH_API_PASSWORD', 'apiuser'),
            'base_uri' => env('OMEGAQUANT_ZH_WEBAPI_BASE_URI', 'http://localhost:15300')
        ]

    ],

    'default_location' => env('OMEGAQUANT_API_DEFAULT_LOCATION', 'us'),
    'default_country' => env('OMEGAQUANT_API_DEFAULT_COUNTRY', 'usa'),
    'default_locale' => env('OMEGAQUANT_API_DEFAULT_LOCALE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Web Api Routes
    |--------------------------------------------------------------------------
    |
    */
    'routes' => [
        'auth-token' => 'api/v1/token',
        'create-patient' =>  'api/v1/patients',
        'get-patient-by-email' => 'api/v1/patients/email/%s',
        'barcode-inquiry' => 'api/v1/barcodeinquiry/%s'
    ],
];
