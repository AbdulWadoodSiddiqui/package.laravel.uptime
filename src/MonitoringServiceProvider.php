<?php

namespace Uptime\Monitoring;

use Illuminate\Support\ServiceProvider;
use Uptime\Monitoring\Console\Commands\InstallCommand;
use Uptime\Monitoring\Console\Commands\PublishCommand;

class MonitoringServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/monitoring.php', 'monitoring');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish configuration
        $this->publishes([
            __DIR__.'/../config/monitoring.php' => config_path('monitoring.php'),
        ], 'config');

        // Publish migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'migrations');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Register middleware
        $this->app['router']->aliasMiddleware('monitoring', \Uptime\Monitoring\Middleware\MonitoringMiddleware::class);
        $this->app['router']->aliasMiddleware('api-key-auth', \Uptime\Monitoring\Middleware\ApiKeyAuthentication::class);

        // Register console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                PublishCommand::class,
            ]);
        }
    }
}
