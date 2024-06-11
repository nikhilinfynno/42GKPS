<?php

return [
    'PAGINATION' => [
        'PER_PAGE' => env('PER_PAGE_POST',10)
    ],
    'MSG91_AUTH_KEY' => env('MSG91_AUTH_KEY'),
    'OTP_TEMPLATE_ID' => env('OTP_TEMPLATE_ID'),
    'DATE_FILTERS' => [
        'current_week' => 'Current Week',
        'current_month' => 'Current Month',
        'last_7_days' => 'Last 7 Days',
        'last_30_days' => 'Last 30 Days',
        'custom' => 'Custom',
    ],
    'DEFAULT_CURRENCY_SYMBOL' => '₹',
    'DEFAULT_CURRENCY' => 'INR',
    'DEFAULT_TIMEZONE' => 'Asia/Kolkata',
    'APP_SETTINGS_SEEDER' => [
        'android' => [
            'latest_version' => "1.0.0",
            'is_force_update' => 0,
            'info' => "<p>Get ready for a smoother, faster experience with our latest update. Tap <strong>Update now</strong> to enjoy the best version of <strong>".config('app.name')."</strong>!</p>",
            'app_url' => urlencode("https://play.google.com/store/apps/details?id=com.medentry.app")
        ],
        'ios' => [
            'latest_version' => "1.0.0",
            'is_force_update' => 0,
            'info' => "<p>Get ready for a smoother, faster experience with our latest update. Tap <strong>Update now</strong> to enjoy the best version of <strong>".config('app.name')."</strong>!</p>",
            'app_url' => urlencode("https://apps.apple.com/us/app/medentry/id1439388560")
        ]
    ],
    'PREFIX' => [
        'Mr',
        'Mrs',
        'Ms',
        'Miss',
    ],
    'GENDERS' => [
        '1'=>'Male',
        '2'=>'Female'    
    ],
    'BLOOD_GROUPS' => [
        '1'=> 'A positive (A+)',
        '2'=> 'A negative (A-)',   
        '3'=> 'B positive (B+)',   
        '4'=> 'B negative (B-)',   
        '5'=> 'O positive (O+)',   
        '6'=> 'O negative (O-)',   
        '7'=> 'AB positive (AB+)',   
        '8'=> 'AB negative (AB-)',   
    ],
    'BLOOD_GROUPS_SHORT' => [
        '1'=> 'A+',
        '2'=> 'A-',   
        '3'=> 'B+',   
        '4'=> 'B-',   
        '5'=> 'O+',   
        '6'=> 'O-',   
        '7'=> 'AB+',   
        '8'=> 'AB-',   
    ],
    'MARITAL_STATUS' => [
        '1'=>'Married',
        '2'=>'Unmarried',    
        '3'=>'Divorced',    
        '4'=>'Widowed'    
    ]
        
];
