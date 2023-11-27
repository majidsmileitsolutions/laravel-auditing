<?php

namespace OwenIt\Auditing\Drivers;

use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class ElasticsearchClient
{
    /**
     * @throws Exception
     */
    public static function build(string $username = null, string $password = null): PendingRequest
    {
        try {
            return config('elasticsearch.has_credentials')
                ? Http::withBasicAuth($username, $password)
                : Http::acceptJson();
        } catch (Exception $exception) {
            throw new Exception('failed_to_connect_to_elasticsearch: ' . $exception->getMessage());
        }
    }
}
