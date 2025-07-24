<?php

return [
    /*
    |--------------------------------------------------------------------------
    | FIFA API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for FIFA API integration including authentication,
    | endpoints, and transfer system settings.
    |
    */

    'api' => [
        'base_url' => env('FIFA_API_BASE_URL', 'https://api.fifa.com/v1'),
        'client_id' => env('FIFA_CLIENT_ID', ''),
        'client_secret' => env('FIFA_CLIENT_SECRET', ''),
        'redirect_uri' => env('FIFA_REDIRECT_URI', 'http://localhost:8000/fifa/callback'),
        'scope' => env('FIFA_SCOPE', 'read write'),
        'timeout' => env('FIFA_API_TIMEOUT', 30),
        'retry_attempts' => env('FIFA_API_RETRY_ATTEMPTS', 3),
    ],

    /*
    |--------------------------------------------------------------------------
    | FIFA Authentication Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for FIFA OAuth authentication and token management.
    |
    */

    'auth' => [
        'enabled' => env('FIFA_AUTH_ENABLED', true),
        'timeout' => env('FIFA_AUTH_TIMEOUT', 3600),
        'refresh_threshold' => env('FIFA_AUTH_REFRESH_THRESHOLD', 300),
        'token_storage' => env('FIFA_TOKEN_STORAGE', 'database'),
        'auto_refresh' => env('FIFA_AUTO_REFRESH', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | FIFA Transfer System Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the FIFA transfer system including windows,
    | fees, and validation rules.
    |
    */

    'transfer' => [
        'enabled' => env('FIFA_TRANSFER_ENABLED', true),
        'window_open' => env('FIFA_TRANSFER_WINDOW_OPEN', true),
        'deadline' => env('FIFA_TRANSFER_DEADLINE', '2025-01-31'),
        'max_fee' => env('FIFA_TRANSFER_MAX_FEE', 200000000),
        'min_fee' => env('FIFA_TRANSFER_MIN_FEE', 0),
        'currency' => env('FIFA_TRANSFER_CURRENCY', 'EUR'),
        'auto_approval' => env('FIFA_TRANSFER_AUTO_APPROVAL', false),
        'require_medical' => env('FIFA_TRANSFER_REQUIRE_MEDICAL', true),
        'require_documents' => env('FIFA_TRANSFER_REQUIRE_DOCUMENTS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | FIFA Sync Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for synchronizing data with FIFA systems.
    |
    */

    'sync' => [
        'enabled' => env('FIFA_SYNC_ENABLED', true),
        'interval' => env('FIFA_SYNC_INTERVAL', 3600),
        'batch_size' => env('FIFA_SYNC_BATCH_SIZE', 100),
        'max_retries' => env('FIFA_SYNC_MAX_RETRIES', 3),
        'entities' => [
            'players' => true,
            'clubs' => true,
            'competitions' => true,
            'transfers' => true,
            'contracts' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | FIFA Webhook Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for FIFA webhook notifications.
    |
    */

    'webhook' => [
        'enabled' => env('FIFA_WEBHOOK_ENABLED', false),
        'secret' => env('FIFA_WEBHOOK_SECRET', ''),
        'url' => env('FIFA_WEBHOOK_URL', ''),
        'events' => [
            'transfer_created' => true,
            'transfer_updated' => true,
            'transfer_completed' => true,
            'contract_signed' => true,
            'player_registered' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | FIFA Document Storage Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for storing FIFA-related documents.
    |
    */

    'documents' => [
        'storage' => env('FIFA_DOCUMENT_STORAGE', 'local'),
        'path' => env('FIFA_DOCUMENT_PATH', 'storage/app/fifa/documents'),
        'max_size' => env('FIFA_DOCUMENT_MAX_SIZE', 10485760), // 10MB
        'allowed_types' => [
            'pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif'
        ],
        'encryption' => env('FIFA_DOCUMENT_ENCRYPTION', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | FIFA Payment Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for FIFA payment processing.
    |
    */

    'payment' => [
        'enabled' => env('FIFA_PAYMENT_ENABLED', true),
        'currency' => env('FIFA_PAYMENT_CURRENCY', 'EUR'),
        'gateway' => env('FIFA_PAYMENT_GATEWAY', 'stripe'),
        'webhook_secret' => env('FIFA_PAYMENT_WEBHOOK_SECRET', ''),
        'auto_confirm' => env('FIFA_PAYMENT_AUTO_CONFIRM', false),
        'commission_rate' => env('FIFA_PAYMENT_COMMISSION_RATE', 5.0),
    ],

    /*
    |--------------------------------------------------------------------------
    | FIFA Audit Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for FIFA audit logging and compliance.
    |
    */

    'audit' => [
        'enabled' => env('FIFA_AUDIT_ENABLED', true),
        'log_level' => env('FIFA_AUDIT_LOG_LEVEL', 'info'),
        'retention_days' => env('FIFA_AUDIT_RETENTION_DAYS', 365),
        'log_sensitive_data' => env('FIFA_AUDIT_LOG_SENSITIVE_DATA', false),
        'encrypt_logs' => env('FIFA_AUDIT_ENCRYPT_LOGS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | FIFA Notification Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for FIFA notification system.
    |
    */

    'notifications' => [
        'enabled' => env('FIFA_NOTIFICATION_ENABLED', true),
        'email' => env('FIFA_NOTIFICATION_EMAIL', true),
        'sms' => env('FIFA_NOTIFICATION_SMS', false),
        'push' => env('FIFA_NOTIFICATION_PUSH', false),
        'channels' => [
            'transfer_updates' => ['email', 'database'],
            'contract_updates' => ['email', 'database'],
            'payment_updates' => ['email', 'database'],
            'system_alerts' => ['email', 'database'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | FIFA Security Configuration
    |--------------------------------------------------------------------------
    |
    | Security settings for FIFA integration.
    |
    */

    'security' => [
        'enabled' => env('FIFA_SECURITY_ENABLED', true),
        'ip_whitelist' => env('FIFA_SECURITY_IP_WHITELIST', ''),
        'rate_limit' => env('FIFA_SECURITY_RATE_LIMIT', 100),
        'rate_limit_window' => env('FIFA_SECURITY_RATE_LIMIT_WINDOW', 3600),
        'require_ssl' => env('FIFA_SECURITY_REQUIRE_SSL', true),
        'api_key_required' => env('FIFA_SECURITY_API_KEY_REQUIRED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | FIFA Development Configuration
    |--------------------------------------------------------------------------
    |
    | Development and testing settings for FIFA integration.
    |
    */

    'development' => [
        'dev_mode' => env('FIFA_DEV_MODE', false),
        'mock_responses' => env('FIFA_DEV_MOCK_RESPONSES', false),
        'log_requests' => env('FIFA_DEV_LOG_REQUESTS', true),
        'log_responses' => env('FIFA_DEV_LOG_RESPONSES', false),
        'test_credentials' => [
            'client_id' => env('FIFA_TEST_CLIENT_ID', ''),
            'client_secret' => env('FIFA_TEST_CLIENT_SECRET', ''),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | FIFA Endpoints Configuration
    |--------------------------------------------------------------------------
    |
    | FIFA API endpoints configuration.
    |
    */

    'endpoints' => [
        'oauth_token' => '/oauth/token',
        'oauth_refresh' => '/oauth/refresh',
        'players' => '/players',
        'clubs' => '/clubs',
        'competitions' => '/competitions',
        'transfers' => '/transfers',
        'contracts' => '/contracts',
        'documents' => '/documents',
        'payments' => '/payments',
        'webhooks' => '/webhooks',
    ],

    /*
    |--------------------------------------------------------------------------
    | FIFA Status Codes
    |--------------------------------------------------------------------------
    |
    | FIFA API status codes and their meanings.
    |
    */

    'status_codes' => [
        'pending' => 'PENDING',
        'approved' => 'APPROVED',
        'rejected' => 'REJECTED',
        'completed' => 'COMPLETED',
        'cancelled' => 'CANCELLED',
        'suspended' => 'SUSPENDED',
        'expired' => 'EXPIRED',
        'terminated' => 'TERMINATED',
    ],

    /*
    |--------------------------------------------------------------------------
    | FIFA Transfer Types
    |--------------------------------------------------------------------------
    |
    | Available FIFA transfer types.
    |
    */

    'transfer_types' => [
        'permanent' => 'PERMANENT',
        'loan' => 'LOAN',
        'free_transfer' => 'FREE_TRANSFER',
        'exchange' => 'EXCHANGE',
    ],

    /*
    |--------------------------------------------------------------------------
    | FIFA Contract Types
    |--------------------------------------------------------------------------
    |
    | Available FIFA contract types.
    |
    */

    'contract_types' => [
        'professional' => 'PROFESSIONAL',
        'amateur' => 'AMATEUR',
        'youth' => 'YOUTH',
        'loan' => 'LOAN',
    ],
]; 