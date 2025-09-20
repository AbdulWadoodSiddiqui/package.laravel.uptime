<?php

namespace Uptime\Monitoring\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'monitoring:install';

    /**
     * The console command description.
     */
    protected $description = 'Install the Uptime Monitoring package';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Installing Uptime Monitoring package...');

        // Publish configuration
        $this->call('vendor:publish', [
            '--provider' => 'Uptime\Monitoring\MonitoringServiceProvider',
            '--tag' => 'config'
        ]);

        // Publish migrations
        $this->call('vendor:publish', [
            '--provider' => 'Uptime\Monitoring\MonitoringServiceProvider',
            '--tag' => 'migrations'
        ]);

        // Run migrations
        if ($this->confirm('Would you like to run the migrations now?')) {
            $this->call('migrate');
        }

        $this->info('Package installed successfully!');
        $this->line('');
        $this->line('Next steps:');
        $this->line('1. Configure your monitoring settings in config/monitoring.php');
        $this->line('2. Add the monitoring middleware to your routes');
        $this->line('3. Set up your monitoring platform API endpoints');

        return 0;
    }
}
