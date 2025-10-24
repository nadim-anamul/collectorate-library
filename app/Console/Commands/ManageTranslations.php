<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TranslationService;

class ManageTranslations extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'trans:manage 
                            {action : Action to perform (add|list|missing|sync|export|import)}
                            {--key= : Translation key (for add action)}
                            {--value= : Translation value (for add action)}
                            {--locale= : Target locale (defaults to current locale)}
                            {--file= : File path for import/export}';

    /**
     * The console command description.
     */
    protected $description = 'Manage JSON translations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $locale = $this->option('locale') ?? app()->getLocale();
        
        // Set locale temporarily
        $originalLocale = app()->getLocale();
        app()->setLocale($locale);
        
        $service = app(TranslationService::class);
        
        try {
            switch ($action) {
                case 'add':
                    $this->handleAdd($service);
                    break;
                case 'list':
                    $this->handleList($service);
                    break;
                case 'missing':
                    $this->handleMissing($service);
                    break;
                case 'sync':
                    $this->handleSync($service);
                    break;
                case 'export':
                    $this->handleExport($service);
                    break;
                case 'import':
                    $this->handleImport($service);
                    break;
                default:
                    $this->error("Unknown action: {$action}");
                    $this->showHelp();
            }
        } finally {
            // Restore original locale
            app()->setLocale($originalLocale);
        }
    }
    
    protected function handleAdd(TranslationService $service)
    {
        $key = $this->option('key');
        $value = $this->option('value');
        
        if (!$key || !$value) {
            $key = $this->ask('Enter translation key');
            $value = $this->ask('Enter translation value');
        }
        
        $service->set($key, $value);
        $this->info("Translation added: {$key} = {$value}");
    }
    
    protected function handleList(TranslationService $service)
    {
        $translations = $service->all();
        
        if (empty($translations)) {
            $this->info('No translations found.');
            return;
        }
        
        $headers = ['Key', 'Value'];
        $rows = [];
        
        foreach ($translations as $key => $value) {
            $rows[] = [$key, $value];
        }
        
        $this->table($headers, $rows);
    }
    
    protected function handleMissing(TranslationService $service)
    {
        $missing = $service->getMissingTranslations();
        
        if (empty($missing)) {
            $this->info('No missing translations found.');
            return;
        }
        
        $this->info('Missing translations:');
        $headers = ['Key', 'English Value'];
        $rows = [];
        
        foreach ($missing as $key => $value) {
            $rows[] = [$key, $value];
        }
        
        $this->table($headers, $rows);
    }
    
    protected function handleSync(TranslationService $service)
    {
        if ($service->syncWithEnglish()) {
            $this->info('Translations synced with English file.');
        } else {
            $this->error('Failed to sync translations.');
        }
    }
    
    protected function handleExport(TranslationService $service)
    {
        $file = $this->option('file') ?? "translations_{$service->currentLocale}.json";
        
        $translations = $service->exportToArray();
        $jsonContent = json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        file_put_contents($file, $jsonContent);
        $this->info("Translations exported to: {$file}");
    }
    
    protected function handleImport(TranslationService $service)
    {
        $file = $this->option('file');
        
        if (!$file || !file_exists($file)) {
            $this->error('Please specify a valid file path with --file option.');
            return;
        }
        
        $content = file_get_contents($file);
        $translations = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Invalid JSON file.');
            return;
        }
        
        $service->importFromArray($translations);
        $this->info("Translations imported from: {$file}");
    }
    
    protected function showHelp()
    {
        $this->info('Available actions:');
        $this->line('  add     - Add a new translation');
        $this->line('  list    - List all translations');
        $this->line('  missing - Show missing translations');
        $this->line('  sync    - Sync with English file');
        $this->line('  export  - Export translations to file');
        $this->line('  import  - Import translations from file');
        
        $this->info('Examples:');
        $this->line('  php artisan trans:manage add --key="Hello World" --value="হ্যালো বিশ্ব" --locale=bn');
        $this->line('  php artisan trans:manage list --locale=en');
        $this->line('  php artisan trans:manage missing --locale=bn');
        $this->line('  php artisan trans:manage sync --locale=bn');
        $this->line('  php artisan trans:manage export --file=backup.json');
        $this->line('  php artisan trans:manage import --file=backup.json');
    }
}
