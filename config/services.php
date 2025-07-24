<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'fifa_connect' => [
        'base_url' => env('FIFA_CONNECT_BASE_URL', 'https://api.fifa.com/v1'),
        'api_key' => env('FIFA_CONNECT_API_KEY'),
        'timeout' => env('FIFA_CONNECT_TIMEOUT', 30),
        'rate_limit_delay' => env('FIFA_CONNECT_RATE_LIMIT_DELAY', 1),
        'cache_ttl' => env('FIFA_CONNECT_CACHE_TTL', 3600),
        'retry_attempts' => env('FIFA_CONNECT_RETRY_ATTEMPTS', 3),
        'retry_delay' => env('FIFA_CONNECT_RETRY_DELAY', 5),
        'webhook_secret' => env('FIFA_WEBHOOK_SECRET'),
        'compliance_check' => env('FIFA_COMPLIANCE_CHECK', true),
        'mock_mode' => env('FIFA_CONNECT_MOCK_MODE', false),
    ],

    'hl7_fhir' => [
        'base_url' => env('HL7_FHIR_BASE_URL', 'https://fhir.example.com'),
        'client_id' => env('HL7_FHIR_CLIENT_ID'),
        'client_secret' => env('HL7_FHIR_CLIENT_SECRET'),
        'timeout' => env('HL7_FHIR_TIMEOUT', 30),
        'retry_attempts' => env('HL7_FHIR_RETRY_ATTEMPTS', 3),
        'retry_delay' => env('HL7_FHIR_RETRY_DELAY', 5),
        'audit_logging' => env('HL7_FHIR_AUDIT_LOGGING', true),
        'batch_size' => env('HL7_FHIR_BATCH_SIZE', 100),
        'version' => env('HL7_FHIR_VERSION', 'R4'),
    ],

];
