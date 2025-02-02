<?php

return [
    'base_url' => env('ELASTIC_HOST', 'http://elasticsearch:9200'),
    'audits_index' => env('APP_NAME', 'your_app_name').'_'.env('APP_ENV', 'your_app_env').'_audits',
    'username' => env('ELASTIC_USERNAME', 'sample_username'),
    'password' => env('ELASTIC_PASSWORD', 'sample_password'),
    'has_credentials' => true,
];
