<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class TranslationService
{
    protected $translations = [];
    protected $currentLocale;
    
    public function __construct()
    {
        $this->currentLocale = app()->getLocale();
        $this->loadTranslations();
    }
    
    /**
     * Load translations from JSON files
     */
    protected function loadTranslations()
    {
        $cacheKey = "translations_{$this->currentLocale}";
        
        $this->translations = Cache::remember($cacheKey, 3600, function () {
            $filePath = resource_path("lang/{$this->currentLocale}.json");
            
            if (File::exists($filePath)) {
                $content = File::get($filePath);
                return json_decode($content, true) ?? [];
            }
            
            return [];
        });
    }
    
    /**
     * Get translation for a key
     */
    public function get($key, $default = null)
    {
        return $this->translations[$key] ?? $default ?? $key;
    }
    
    /**
     * Add or update a translation
     */
    public function set($key, $value)
    {
        $this->translations[$key] = $value;
        $this->saveTranslations();
    }
    
    /**
     * Add multiple translations
     */
    public function setMany(array $translations)
    {
        foreach ($translations as $key => $value) {
            $this->translations[$key] = $value;
        }
        $this->saveTranslations();
    }
    
    /**
     * Save translations to JSON file
     */
    protected function saveTranslations()
    {
        $filePath = resource_path("lang/{$this->currentLocale}.json");
        
        // Sort translations alphabetically by key
        ksort($this->translations);
        
        $jsonContent = json_encode($this->translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        File::put($filePath, $jsonContent);
        
        // Clear cache
        Cache::forget("translations_{$this->currentLocale}");
    }
    
    /**
     * Get all translations
     */
    public function all()
    {
        return $this->translations;
    }
    
    /**
     * Check if translation exists
     */
    public function has($key)
    {
        return isset($this->translations[$key]);
    }
    
    /**
     * Get missing translations (keys that exist in English but not in current locale)
     */
    public function getMissingTranslations()
    {
        $englishFile = resource_path('lang/en.json');
        $currentFile = resource_path("lang/{$this->currentLocale}.json");
        
        if (!File::exists($englishFile)) {
            return [];
        }
        
        $englishTranslations = json_decode(File::get($englishFile), true) ?? [];
        $currentTranslations = json_decode(File::get($currentFile), true) ?? [];
        
        return array_diff_key($englishTranslations, $currentTranslations);
    }
    
    /**
     * Sync translations with English file
     */
    public function syncWithEnglish()
    {
        $englishFile = resource_path('lang/en.json');
        $currentFile = resource_path("lang/{$this->currentLocale}.json");
        
        if (!File::exists($englishFile)) {
            return false;
        }
        
        $englishTranslations = json_decode(File::get($englishFile), true) ?? [];
        $currentTranslations = json_decode(File::get($currentFile), true) ?? [];
        
        // Add missing keys with empty values
        foreach ($englishTranslations as $key => $value) {
            if (!isset($currentTranslations[$key])) {
                $currentTranslations[$key] = ''; // Empty value for translation
            }
        }
        
        // Sort and save
        ksort($currentTranslations);
        $jsonContent = json_encode($currentTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        File::put($currentFile, $jsonContent);
        
        // Clear cache
        Cache::forget("translations_{$this->currentLocale}");
        
        return true;
    }
    
    /**
     * Export translations to array format
     */
    public function exportToArray()
    {
        return $this->translations;
    }
    
    /**
     * Import translations from array
     */
    public function importFromArray(array $translations)
    {
        $this->translations = array_merge($this->translations, $translations);
        $this->saveTranslations();
    }
}
