<?php

return [
    'base_url' => env('ELASTIC_HOST', 'http://elasticsearch:9200'),
    'audits_index' => env('APP_NAME').'_'.env('APP_ENV').'_'.env('audits'),
];
