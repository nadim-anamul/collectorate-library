<?php

namespace Database\Seeders;

use App\Models\Models\Book;
use App\Models\Models\Category;
use App\Models\Models\Member;
use App\Models\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DemoSeeder extends Seeder
{
    public function run()
    {
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => Hash::make('password')]
        );
        if(!$admin->hasRole('Admin')){ $admin->assignRole($adminRole); }

        $fiction = Category::firstOrCreate(['slug' => 'fiction'], ['name_en' => 'Fiction', 'name_bn' => 'উপন্যাস']);
        $tagClassic = Tag::firstOrCreate(['slug' => 'classic'], ['name' => 'Classic']);

        $book = Book::firstOrCreate([
            'title_en' => 'Shesher Kobita',
            'title_bn' => 'শেষের কবিতা',
        ], [
            'title_bn_translit' => 'Shesher Kobita',
            'author_en' => 'Rabindranath Tagore',
            'author_bn' => 'রবীন্দ্রনাথ ঠাকুর',
            'category_id' => $fiction->id,
            'publisher_en' => 'Visva-Bharati',
            'publisher_bn' => 'বিশ্বভারতী',
            'isbn' => '9780195661330',
            'barcode' => '9780195661330',
            'publication_year' => 1929,
            'pages' => 220,
            'language_primary' => 'bn',
            'available_copies' => 3,
            'total_copies' => 3,
        ]);
        $book->tags()->syncWithoutDetaching([$tagClassic->id]);

        Member::firstOrCreate(['member_id' => 'M-1001'], ['name' => 'Demo Member', 'type' => 'Student', 'active' => true]);
    }
}
