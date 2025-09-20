<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Monitoring Platform URL
    |--------------------------------------------------------------------------
    |
    | The base URL of your monitoring platform where data will be sent.
    |
    */
    'base_url' => env('MONITORING_BASE_URL', 'https://your-monitoring-platform.com'),

    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | The API key for authenticating with the monitoring platform.
    |
    */
    'api_key' => env('MONITORING_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Timeout
    |--------------------------------------------------------------------------
    |
    | Request timeout in seconds for API calls to the monitoring platform.
    |
    */
    'timeout' => env('MONITORING_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Retry Attempts
    |--------------------------------------------------------------------------
    |
    | Number of times to retry failed requests to the monitoring platform.
    |
    */
    'retry_attempts' => env('MONITORING_RETRY_ATTEMPTS', 3),

    /*
    |--------------------------------------------------------------------------
    | Retry Delay
    |--------------------------------------------------------------------------
    |
    | Delay in seconds between retry attempts.
    |
    */
    'retry_delay' => env('MONITORING_RETRY_DELAY', 5),

    /*
    |--------------------------------------------------------------------------
    | Enable Logging
    |--------------------------------------------------------------------------
    |
    | Whether to log monitoring client activities.
    |
    */
    'enable_logging' => env('MONITORING_CLIENT_LOGGING', true),

    /*
    |--------------------------------------------------------------------------
    | Log Channel
    |--------------------------------------------------------------------------
    |
    | The log channel to use for monitoring client logs.
    |
    */
    'log_channel' => env('MONITORING_LOG_CHANNEL', 'default'),
];
