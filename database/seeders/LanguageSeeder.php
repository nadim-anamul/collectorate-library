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
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }
    }
}
