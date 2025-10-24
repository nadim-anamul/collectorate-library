<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register Blade directive for automatic Bengali number conversion
        Blade::directive('bn', function ($expression) {
            return "<?php echo app()->getLocale() === 'bn' ? toBengaliNumbers($expression) : $expression; ?>";
        });
        
        // Register Blade directive for automatic Bengali number conversion with fallback
        Blade::directive('bnf', function ($expression) {
            return "<?php echo app()->getLocale() === 'bn' ? toBengaliNumbers($expression) : $expression; ?>";
        });
    }
}
