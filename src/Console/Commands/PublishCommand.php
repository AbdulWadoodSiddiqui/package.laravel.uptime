<?php

namespace Uptime\Monitoring\Console\Commands;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'monitoring:publish {--force : Overwrite existing files}';

    /**
     * The console command description.
     */
    protected $description = 'Publish all monitoring package assets';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Publishing Uptime Monitoring package assets...');

        // Publish configuration
        $this->call('vendor:publish', [
            '--provider' => 'Uptime\Monitoring\MonitoringServiceProvider',
            '--tag' => 'config',
            '--force' => $this->option('force')
        ]);

        // Publish migrations
        $this->call('vendor:publish', [
            '--provider' => 'Uptime\Monitoring\MonitoringServiceProvider',
            '--tag' => 'migrations',
            '--force' => $this->option('force')
        ]);

        $this->info('Assets published successfully!');

        return 0;
    }
}
