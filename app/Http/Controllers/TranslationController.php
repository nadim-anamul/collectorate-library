<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TranslationService;

class TranslationController extends Controller
{
    protected $translationService;
    
    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }
    
    /**
     * Show translation management interface
     */
    public function index()
    {
        $currentLocale = app()->getLocale();
        $translations = $this->translationService->all();
        $missingTranslations = $this->translationService->getMissingTranslations();
        
        return view('admin.translations.index', compact('translations', 'missingTranslations', 'currentLocale'));
    }
    
    /**
     * Add or update a translation
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|max:255',
            'value' => 'required|string',
            'locale' => 'required|string|in:en,bn'
        ]);
        
        // Set locale temporarily
        $originalLocale = app()->getLocale();
        app()->setLocale($request->locale);
        
        $this->translationService->set($request->key, $request->value);
        
        // Restore original locale
        app()->setLocale($originalLocale);
        
        return response()->json([
            'success' => true,
            'message' => 'Translation saved successfully'
        ]);
    }
    
    /**
     * Delete a translation
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'locale' => 'required|string|in:en,bn'
        ]);
        
        // Set locale temporarily
        $originalLocale = app()->getLocale();
        app()->setLocale($request->locale);
        
        $translations = $this->translationService->all();
        unset($translations[$request->key]);
        
        // Save updated translations
        $this->translationService->importFromArray($translations);
        
        // Restore original locale
        app()->setLocale($originalLocale);
        
        return response()->json([
            'success' => true,
            'message' => 'Translation deleted successfully'
        ]);
    }
    
    /**
     * Sync translations with English
     */
    public function sync(Request $request)
    {
        $request->validate([
            'locale' => 'required|string|in:en,bn'
        ]);
        
        // Set locale temporarily
        $originalLocale = app()->getLocale();
        app()->setLocale($request->locale);
        
        $result = $this->translationService->syncWithEnglish();
        
        // Restore original locale
        app()->setLocale($originalLocale);
        
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Translations synced successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync translations'
            ], 500);
        }
    }
    
    /**
     * Export translations
     */
    public function export(Request $request)
    {
        $request->validate([
            'locale' => 'required|string|in:en,bn'
        ]);
        
        // Set locale temporarily
        $originalLocale = app()->getLocale();
        app()->setLocale($request->locale);
        
        $translations = $this->translationService->all();
        
        // Restore original locale
        app()->setLocale($originalLocale);
        
        $fileName = "translations_{$request->locale}_" . date('Y-m-d_H-i-s') . '.json';
        
        return response()->json($translations)
            ->header('Content-Disposition', "attachment; filename={$fileName}")
            ->header('Content-Type', 'application/json');
    }
    
    /**
     * Import translations
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:json|max:10240', // 10MB max
            'locale' => 'required|string|in:en,bn'
        ]);
        
        try {
            $content = file_get_contents($request->file('file')->path());
            $translations = json_decode($content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid JSON file'
                ], 400);
            }
            
            // Set locale temporarily
            $originalLocale = app()->getLocale();
            app()->setLocale($request->locale);
            
            $this->translationService->importFromArray($translations);
            
            // Restore original locale
            app()->setLocale($originalLocale);
            
            return response()->json([
                'success' => true,
                'message' => 'Translations imported successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to import translations: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get translations for API
     */
    public function api(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        // Set locale temporarily
        $originalLocale = app()->getLocale();
        app()->setLocale($locale);
        
        $translations = $this->translationService->all();
        
        // Restore original locale
        app()->setLocale($originalLocale);
        
        return response()->json($translations);
    }
}
