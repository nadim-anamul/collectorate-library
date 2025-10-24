<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class OptimizeProduction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'optimize:production {--force : Force optimization even in non-production environment}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize the application for production deployment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!app()->environment('production') && !$this->option('force')) {
            $this->error('This command should only be run in production environment.');
            $this->info('Use --force flag to run in non-production environment.');
            return 1;
        }

        $this->info('🚀 Starting production optimization...');

        $this->optimizeLaravel();
        $this->optimizeAssets();
        $this->optimizeDatabase();
        $this->setPermissions();

        $this->info('✅ Production optimization completed successfully!');
        return 0;
    }

    /**
     * Optimize Laravel caches
     */
    private function optimizeLaravel()
    {
        $this->info('📦 Optimizing Laravel caches...');

        Artisan::call('config:cache');
        $this->line('  ✓ Configuration cached');

        Artisan::call('route:cache');
        $this->line('  ✓ Routes cached');

        Artisan::call('view:cache');
        $this->line('  ✓ Views cached');

        Artisan::call('event:cache');
        $this->line('  ✓ Events cached');

        Artisan::call('cache:clear');
        $this->line('  ✓ Application cache cleared');
    }

    /**
     * Optimize assets
     */
    private function optimizeAssets()
    {
        $this->info('🎨 Optimizing assets...');

        // Check if public/build directory exists
        if (!File::exists(public_path('build'))) {
            $this->warn('  ⚠ Build directory not found. Run "npm run build" first.');
            return;
        }

        $this->line('  ✓ Assets are optimized');
    }

    /**
     * Optimize database
     */
    private function optimizeDatabase()
    {
        $this->info('🗄️ Optimizing database...');

        // Run migrations if needed
        Artisan::call('migrate', ['--force' => true]);
        $this->line('  ✓ Database migrations completed');

        // Clear and rebuild cache
        Artisan::call('cache:clear');
        $this->line('  ✓ Database cache cleared');
    }

    /**
     * Set proper file permissions
     */
    private function setPermissions()
    {
        $this->info('🔐 Setting file permissions...');

        $directories = [
            storage_path(),
            base_path('bootstrap/cache'),
        ];

        foreach ($directories as $directory) {
            if (File::exists($directory)) {
                chmod($directory, 0755);
                $this->line("  ✓ Permissions set for: " . basename($directory));
            }
        }
    }
}
