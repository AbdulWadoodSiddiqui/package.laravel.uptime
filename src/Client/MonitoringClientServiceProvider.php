<?php

namespace Uptime\Monitoring\Client;

use Illuminate\Support\ServiceProvider;

class MonitoringClientServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/monitoring-client.php', 'monitoring-client');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish configuration
        $this->publishes([
            __DIR__.'/../config/monitoring-client.php' => config_path('monitoring-client.php'),
        ], 'config');
    }
}
