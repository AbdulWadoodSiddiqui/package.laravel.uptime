<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Monitoring Enabled
    |--------------------------------------------------------------------------
    |
    | This option controls whether monitoring is enabled for your application.
    | When disabled, no monitoring data will be collected.
    |
    */
    'enabled' => env('MONITORING_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Log Errors Only
    |--------------------------------------------------------------------------
    |
    | When this option is enabled, only requests that result in errors
    | (status codes 400 and above) will be logged.
    |
    */
    'log_errors_only' => env('MONITORING_LOG_ERRORS_ONLY', false),

    /*
    |--------------------------------------------------------------------------
    | Excluded Routes
    |--------------------------------------------------------------------------
    |
    | Here you may specify routes that should be excluded from monitoring.
    | Use wildcard patterns to match multiple routes.
    |
    */
    'excluded_routes' => [
        'monitoring/*',
        'health',
        'ping',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sensitive Headers
    |--------------------------------------------------------------------------
    |
    | Headers that should be redacted from logs for security purposes.
    |
    */
    'sensitive_headers' => [
        'authorization',
        'cookie',
        'x-api-key',
        'x-auth-token',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sensitive Fields
    |--------------------------------------------------------------------------
    |
    | Request/response fields that should be redacted from logs.
    |
    */
    'sensitive_fields' => [
        'password',
        'password_confirmation',
        'token',
        'secret',
        'key',
        'api_key',
        'access_token',
        'refresh_token',
    ],

    /*
    |--------------------------------------------------------------------------
    | Response Body Limit
    |--------------------------------------------------------------------------
    |
    | Maximum size of response body to log (in characters).
    | Set to null for no limit.
    |
    */
    'response_body_limit' => 1000,

    /*
    |--------------------------------------------------------------------------
    | Queue Logging
    |--------------------------------------------------------------------------
    |
    | When enabled, monitoring data will be queued for processing instead
    | of being written directly to the database.
    |
    */
    'queue_logging' => env('MONITORING_QUEUE_LOGGING', false),

    /*
    |--------------------------------------------------------------------------
    | Queue Name
    |--------------------------------------------------------------------------
    |
    | The queue name to use for monitoring jobs.
    |
    */
    'queue_name' => env('MONITORING_QUEUE_NAME', 'default'),
];
