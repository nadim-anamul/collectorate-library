<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Production Optimization Settings
    |--------------------------------------------------------------------------
    |
    | This file contains optimization settings for production deployment.
    | These settings help improve performance and security.
    |
    */

    'cache' => [
        'config' => env('CACHE_CONFIG', true),
        'routes' => env('CACHE_ROUTES', true),
        'views' => env('CACHE_VIEWS', true),
        'events' => env('CACHE_EVENTS', true),
    ],

    'compression' => [
        'gzip' => env('ENABLE_GZIP', true),
        'brotli' => env('ENABLE_BROTLI', false),
    ],

    'assets' => [
        'minify_css' => env('MINIFY_CSS', true),
        'minify_js' => env('MINIFY_JS', true),
        'combine_files' => env('COMBINE_FILES', true),
        'version_assets' => env('VERSION_ASSETS', true),
    ],

    'database' => [
        'query_cache' => env('DB_QUERY_CACHE', true),
        'connection_pooling' => env('DB_CONNECTION_POOLING', true),
        'slow_query_log' => env('DB_SLOW_QUERY_LOG', false),
    ],

    'security' => [
        'force_https' => env('FORCE_HTTPS', true),
        'secure_headers' => env('SECURE_HEADERS', true),
        'csp_enabled' => env('CSP_ENABLED', true),
        'rate_limiting' => env('RATE_LIMITING', true),
    ],

    'monitoring' => [
        'performance_monitoring' => env('PERFORMANCE_MONITORING', true),
        'error_tracking' => env('ERROR_TRACKING', true),
        'uptime_monitoring' => env('UPTIME_MONITORING', true),
    ],
];
