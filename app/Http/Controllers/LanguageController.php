<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch the application language
     */
    public function switchLanguage(Request $request, $locale)
    {
        // Validate that the locale is supported
        if (in_array($locale, ['en', 'bn'])) {
            App::setLocale($locale);
            Session::put('locale', $locale);
        }

        // Redirect back to the previous page or home
        return redirect()->back();
    }

    /**
     * Get current language
     */
    public function getCurrentLanguage()
    {
        return Session::get('locale', config('app.locale'));
    }
}
