<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $authors = [
            [
                'name_en' => 'Rabindranath Tagore',
                'name_bn' => 'রবীন্দ্রনাথ ঠাকুর',
                'bio' => 'Bengali polymath who reshaped Bengali literature and music. Nobel Prize winner in Literature (1913).',
            ],
            [
                'name_en' => 'Kazi Nazrul Islam',
                'name_bn' => 'কাজী নজরুল ইসলাম',
                'bio' => 'Bengali poet, writer, musician, and revolutionary. Known as the National Poet of Bangladesh.',
            ],
            [
                'name_en' => 'Humayun Ahmed',
                'name_bn' => 'হুমায়ূন আহমেদ',
                'bio' => 'Bangladeshi novelist, dramatist, screenwriter, filmmaker and professor.',
            ],
            [
                'name_en' => 'Sarat Chandra Chattopadhyay',
                'name_bn' => 'শরৎচন্দ্র চট্টোপাধ্যায়',
                'bio' => 'Bengali novelist and short story writer of the early 20th century.',
            ],
            [
                'name_en' => 'Bibhutibhushan Bandyopadhyay',
                'name_bn' => 'বিভূতিভূষণ বন্দ্যোপাধ্যায়',
                'bio' => 'Bengali author, known for his novel Pather Panchali.',
            ],
            [
                'name_en' => 'Manik Bandopadhyay',
                'name_bn' => 'মানিক বন্দ্যোপাধ্যায়',
                'bio' => 'Bengali novelist and short story writer.',
            ],
            [
                'name_en' => 'Muhammad Zafar Iqbal',
                'name_bn' => 'মুহম্মদ জাফর ইকবাল',
                'bio' => 'Bangladeshi author, physicist, and educator.',
            ],
            [
                'name_en' => 'Sunil Gangopadhyay',
                'name_bn' => 'সুনীল গঙ্গোপাধ্যায়',
                'bio' => 'Indian Bengali poet and novelist.',
            ],
            [
                'name_en' => 'Anisul Hoque',
                'name_bn' => 'আনিসুল হক',
                'bio' => 'Bangladeshi novelist and journalist.',
            ],
            [
                'name_en' => 'Taslima Nasreen',
                'name_bn' => 'তসলিমা নাসরিন',
                'bio' => 'Bangladeshi-Swedish writer, physician, feminist, and activist.',
            ],
            [
                'name_en' => 'William Shakespeare',
                'name_bn' => 'উইলিয়াম শেক্সপিয়ার',
                'bio' => 'English playwright, poet, and actor, widely regarded as the greatest writer in the English language.',
            ],
            [
                'name_en' => 'Jane Austen',
                'name_bn' => 'জেন অস্টেন',
                'bio' => 'English novelist known for her wit, social commentary and insight into women\'s lives.',
            ],
            [
                'name_en' => 'Charles Dickens',
                'name_bn' => 'চার্লস ডিকেন্স',
                'bio' => 'English writer and social critic who created some of the world\'s best-known fictional characters.',
            ],
            [
                'name_en' => 'George Orwell',
                'name_bn' => 'জর্জ অরওয়েল',
                'bio' => 'English novelist, essayist, journalist, and critic known for 1984 and Animal Farm.',
            ],
            [
                'name_en' => 'Agatha Christie',
                'name_bn' => 'আগাথা ক্রিস্টি',
                'bio' => 'English writer known for her detective novels featuring Hercule Poirot and Miss Marple.',
            ],
        ];

        foreach ($authors as $author) {
            Author::create($author);
        }
    }
}
