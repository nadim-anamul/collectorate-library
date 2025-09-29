<?php

use App\Services\TranslationService;

if (!function_exists('trans_json')) {
    /**
     * Get translation from JSON files with fallback
     */
    function trans_json($key, $default = null, $locale = null)
    {
        if ($locale) {
            $originalLocale = app()->getLocale();
            app()->setLocale($locale);
        }
        
        $translation = app(TranslationService::class)->get($key, $default);
        
        if ($locale) {
            app()->setLocale($originalLocale);
        }
        
        return $translation;
    }
}

if (!function_exists('trans_add')) {
    /**
     * Add a new translation
     */
    function trans_add($key, $value)
    {
        return app(TranslationService::class)->set($key, $value);
    }
}

if (!function_exists('trans_has')) {
    /**
     * Check if translation exists
     */
    function trans_has($key)
    {
        return app(TranslationService::class)->has($key);
    }
}

if (!function_exists('trans_missing')) {
    /**
     * Get missing translations
     */
    function trans_missing()
    {
        return app(TranslationService::class)->getMissingTranslations();
    }
}

if (!function_exists('trans_sync')) {
    /**
     * Sync translations with English
     */
    function trans_sync()
    {
        return app(TranslationService::class)->syncWithEnglish();
    }
}

if (!function_exists('__')) {
    /**
     * Override Laravel's __ function to use JSON translations first
     */
    function __($key, $replace = [], $locale = null)
    {
        // Try JSON translation first
        $translation = trans_json($key, null, $locale);
        
        if ($translation !== $key) {
            // If we found a translation, apply replacements if any
            if (!empty($replace) && is_array($replace)) {
                foreach ($replace as $search => $replacement) {
                    $translation = str_replace(":{$search}", $replacement, $translation);
                }
            }
            return $translation;
        }
        
        // Fallback to Laravel's default translation system
        return \Illuminate\Support\Facades\__($key, $replace, $locale);
    }
}
