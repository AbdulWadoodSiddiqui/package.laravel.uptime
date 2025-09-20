# Uptime Monitoring Laravel Package

A comprehensive Laravel package for monitoring API responses and route access, designed to work with the Uptime Monitoring platform.

## Features

- **API Response Logging**: Track API response times, status codes, and errors
- **Route Access Monitoring**: Monitor web route access patterns and performance
- **Security**: Automatic sanitization of sensitive data (passwords, tokens, etc.)
- **Flexible Configuration**: Customizable logging rules and exclusions
- **Client SDK**: Easy integration with remote monitoring platforms
- **Queue Support**: Optional queued logging for better performance

## Installation

1. Install the package via Composer:

```bash
composer require uptime-uat/monitoring
```

2. Publish the configuration and migrations:

```bash
php artisan monitoring:install
```

3. Run the migrations:

```bash
php artisan migrate
```

4. Configure your monitoring settings in `config/monitoring.php`

## Configuration

### Basic Configuration

```php
// config/monitoring.php
return [
    'enabled' => true,
    'log_errors_only' => false,
    'excluded_routes' => [
        'monitoring/*',
        'health',
    ],
];
```

### Environment Variables

```env
MONITORING_ENABLED=true
MONITORING_LOG_ERRORS_ONLY=false
MONITORING_QUEUE_LOGGING=false
```

## Usage

### Middleware

Add the monitoring middleware to your routes:

```php
// In your routes/web.php
Route::middleware(['monitoring'])->group(function () {
    // Your routes here
});
```

Or apply it globally in your `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \Uptime\Monitoring\Middleware\MonitoringMiddleware::class,
    ]);
})
```

### Client SDK

For sending data to a remote monitoring platform:

```php
use Uptime\Monitoring\Client\MonitoringClient;

$client = new MonitoringClient(
    baseUrl: 'https://your-monitoring-platform.com',
    apiKey: 'your-api-key'
);

// Log API response
$client->logApiResponse([
    'method' => 'GET',
    'url' => 'https://api.example.com/users',
    'status_code' => 200,
    'response_time_ms' => 150.5,
    // ... other data
]);
```

## API Endpoints

The package provides API endpoints for receiving monitoring data:

- `POST /api/log-api-response` - Log API responses
- `POST /api/log-route-access` - Log route access
- `GET /api/stats` - Get project statistics
- `GET /api/health` - Health check

All API endpoints require authentication via the `X-API-Key` header.

## Security

The package automatically sanitizes sensitive data:

- **Headers**: Authorization, Cookie, X-API-Key, etc.
- **Fields**: Password, Token, Secret, Key, etc.
- **Response Bodies**: Limited to prevent logging large responses

## Queue Support

For better performance, you can enable queued logging:

```php
// config/monitoring.php
'queue_logging' => true,
'queue_name' => 'monitoring',
```

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support, please contact us at support@uptimemonitor.com or visit our [documentation](https://your-monitoring-platform.com/documentation).
