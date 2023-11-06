<?php

return [
    'audit_queue' => env('APP_NAME').'_'.env('APP_ENV').'_audit_queue',

    'use' => 'production',

    'properties' => [
        'production' => [
            'host' => env('AMQP_HOST', 'rabbitmq'),
            'port' => env('AMQP_PORT', '5672'),
            'username' => env('AMQP_USERNAME'),
            'password' => env('AMQP_PASSWORD'),
            'vhost' => env('AMQP_VHOST', '/'),
            'connect_options' => [],
            'ssl_options' => [],

            'exchange' => env('AMQP_EXCHANGE', 'amq.topic'),
            'exchange_type' => env('AMQP_EXCHANGE_TYPE', 'topic'),
            'exchange_passive' => false,
            'exchange_durable' => true,
            //'exchange_auto_delete' => true,
            'exchange_internal' => false,
            'exchange_nowait' => false,
            'exchange_properties' => [],

            'queue_force_declare' => false,
            'queue_passive' => false,
            //'queue_durable' => true,
            'queue_exclusive' => false,
            //'queue_auto_delete' => true,
            'queue_nowait' => false,
            //'queue_properties' => ['x-ha-policy' => ['S', 'all'], 'x-message-ttl' => ['I', '864000000'], 'x-queue-mode' => ['S', 'lazy']],

            'consumer_tag' => '',
            'consumer_no_local' => false,
            'consumer_no_ack' => false,
            'consumer_exclusive' => false,
            'consumer_nowait' => false,
            'timeout' => 0,
            'persistent' => false,

            'qos' => false,
            'qos_prefetch_size' => 0,
            'qos_prefetch_count' => 1,
            'qos_a_global' => false
        ],
    ],
];
