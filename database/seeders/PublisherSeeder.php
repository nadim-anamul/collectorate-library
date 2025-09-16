<?php

namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    public function run(): void
    {
        $publishers = [
            [
                'name_en' => 'Ananda Publishers',
                'name_bn' => 'আনন্দ পাবলিশার্স',
                'website' => 'https://anandapublishers.com',
            ],
            [
                'name_en' => 'Prothoma Prokashan',
                'name_bn' => 'প্রথমা প্রকাশন',
                'website' => 'https://prothomaprokashon.com',
            ],
            [
                'name_en' => 'Somoy Prakashan',
                'name_bn' => 'সময় প্রকাশন',
                'website' => null,
            ],
            [
                'name_en' => 'Mowla Brothers',
                'name_bn' => 'মওলা ব্রাদার্স',
                'website' => null,
            ],
            [
                'name_en' => 'Kakoli Prokashoni',
                'name_bn' => 'কাকলী প্রকাশনী',
                'website' => null,
            ],
            [
                'name_en' => 'Onno Prokash',
                'name_bn' => 'অন্য প্রকাশ',
                'website' => 'https://onnoprokash.com',
            ],
            [
                'name_en' => 'Sheba Prokashoni',
                'name_bn' => 'শেবা প্রকাশনী',
                'website' => null,
            ],
            [
                'name_en' => 'Penguin Random House',
                'name_bn' => 'পেঙ্গুইন র‍্যান্ডম হাউস',
                'website' => 'https://penguinrandomhouse.com',
            ],
            [
                'name_en' => 'HarperCollins',
                'name_bn' => 'হার্পারকলিন্স',
                'website' => 'https://harpercollins.com',
            ],
            [
                'name_en' => 'Oxford University Press',
                'name_bn' => 'অক্সফোর্ড ইউনিভার্সিটি প্রেস',
                'website' => 'https://global.oup.com',
            ],
            [
                'name_en' => 'Macmillan Publishers',
                'name_bn' => 'ম্যাকমিলান পাবলিশার্স',
                'website' => 'https://macmillan.com',
            ],
            [
                'name_en' => 'Scholastic',
                'name_bn' => 'স্কলাস্টিক',
                'website' => 'https://scholastic.com',
            ],
            [
                'name_en' => 'Pearson Education',
                'name_bn' => 'পিয়ারসন এডুকেশন',
                'website' => 'https://pearson.com',
            ],
            [
                'name_en' => 'Cambridge University Press',
                'name_bn' => 'কেমব্রিজ ইউনিভার্সিটি প্রেস',
                'website' => 'https://cambridge.org',
            ],
            [
                'name_en' => 'Bloomsbury Publishing',
                'name_bn' => 'ব্লুমসবেরি পাবলিশিং',
                'website' => 'https://bloomsbury.com',
            ],
        ];

        foreach ($publishers as $publisher) {
            Publisher::create($publisher);
        }
    }
}
