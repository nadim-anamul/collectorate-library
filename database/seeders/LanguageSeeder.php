<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            ['code' => 'bn', 'name' => 'Bengali'],
            ['code' => 'en', 'name' => 'English'],
            ['code' => 'hi', 'name' => 'Hindi'],
            ['code' => 'ur', 'name' => 'Urdu'],
            ['code' => 'ar', 'name' => 'Arabic'],
            ['code' => 'fr', 'name' => 'French'],
            ['code' => 'de', 'name' => 'German'],
            ['code' => 'es', 'name' => 'Spanish'],
            ['code' => 'pt', 'name' => 'Portuguese'],
            ['code' => 'ru', 'name' => 'Russian'],
            ['code' => 'zh', 'name' => 'Chinese'],
            ['code' => 'ja', 'name' => 'Japanese'],
            ['code' => 'ko', 'name' => 'Korean'],
            ['code' => 'it', 'name' => 'Italian'],
            ['code' => 'nl', 'name' => 'Dutch'],
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }
    }
}
