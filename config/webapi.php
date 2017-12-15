<?php

return [

    'origins' => [
        'us' => [
            'country' => 'USA',
            'default_locale' => 'en',
            'username' => env('OMEGAQUANT_US_API_USERNAME', 'apiuser'),
            'password' => env('OMEGAQUANT_US_API_PASSWORD', 'apiuser'),
            'base_uri' => env('OMEGAQUANT_US_WEBAPI_BASE_URI', 'http://localhost:15300')
        ],
        'au' => [
            'country' => 'AUS',
            'default_locale' => 'au',
            'username' => env('OMEGAQUANT_AU_API_USERNAME', 'apiuser'),
            'password' => env('OMEGAQUANT_AU_API_PASSWORD', 'apiuser'),
            'base_uri' => env('OMEGAQUANT_AU_WEBAPI_BASE_URI', 'http://localhost:15300')
        ],
        'zh' => [
            'country' => 'CHN',
            'default_locale' => 'zh-CN',
            'username' => env('OMEGAQUANT_ZH_API_USERNAME', 'apiuser'),
            'password' => env('OMEGAQUANT_ZH_API_PASSWORD', 'apiuser'),
            'base_uri' => env('OMEGAQUANT_ZH_WEBAPI_BASE_URI', 'http://localhost:15300')
        ]

    ],

    'default_country' => env('APP_COUNTRY', 'USA'),
    'default_origin' => env('APP_ORIGIN', 'us'),
    'default_locale' => env('APP_LOCALE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Web Api Routes
    |--------------------------------------------------------------------------
    |
    */
    'routes' => [
        'auth-token' => 'api/v1/token',
        'create-patient' =>  'api/v1/patients',
        'create-patient-test' =>  'api/v1/patienttests',
        'get-patient-by-email' => 'api/v1/patients/email/%s',
        'get-patient-by-oqid' => 'api/v1/patients/oqid/%s',
        'barcode-inquiry' => 'api/v1/barcodeinquiry/%s',
        'link-barcode' => 'api/v1/barcodelink',
        'update-patient-result' => 'api/v1/patientresults'
    ],
];
