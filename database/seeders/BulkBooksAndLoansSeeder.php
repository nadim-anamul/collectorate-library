<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Language;
use App\Models\Publisher;
use App\Models\Models\Book;
use App\Models\Models\Category;
use App\Models\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BulkBooksAndLoansSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure base refs exist
        $this->seedBaseReferences();

        // Create/ensure a pool of approved users
        $users = $this->ensureApprovedUsers(20);

        // Create ~150 books with cover images
        $books = $this->createBooksWithCovers(150);

        // Generate loan history
        $this->generateLoanHistory($books, $users);
    }

    protected function seedBaseReferences(): void
    {
        // Minimal sets of categories, languages, publishers, authors
        $categories = [
            'Fiction','Non-Fiction','Poetry','Mystery','Biography','History','Science','Philosophy','Children','Technology'
        ];
        foreach ($categories as $name) {
            Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name_en' => $name, 'name_bn' => $name]
            );
        }

        $langs = [
            ['code' => 'en', 'name' => 'English'],
            ['code' => 'bn', 'name' => 'Bengali'],
            ['code' => 'hi', 'name' => 'Hindi'],
        ];
        foreach ($langs as $l) {
            Language::firstOrCreate(['code' => $l['code']], ['name' => $l['name']]);
        }
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

    protected function ensureApprovedUsers(int $count): \Illuminate\Support\Collection
    {
        $existing = User::where('status','approved')->get();
        $needed = max(0, $count - $existing->count());
        for ($i = 0; $i < $needed; $i++) {
            $name = 'Test User '.Str::padLeft((string)($existing->count() + $i + 1), 4, '0');
            $email = 'user'.Str::padLeft((string)($existing->count() + $i + 1), 4, '0').'@example.com';
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => bcrypt('password'),
                    'status' => 'approved',
                ]
            );
            if (method_exists($user, 'assignRole')) {
                try { $user->assignRole('Member'); } catch (\Throwable $e) {}
            }
        }
        return User::where('status','approved')->get();
    }

    protected function createBooksWithCovers(int $count): \Illuminate\Support\Collection
    {
        $categories = Category::all();
        $publishers = Publisher::all();
        $languages = Language::all();
        $authors = Author::all();

        $books = collect();
        for ($i = 0; $i < $count; $i++) {
            $title = $this->randomTitle();
            $category = $categories->random();
            $publisher = $publishers->random();
            $language = $languages->random();
            $primaryAuthor = $authors->random();

            $totalCopies = rand(1, 8);
            $isbn = $this->fakeIsbn13();

            $coverPath = $this->downloadRandomCover();
            if($language->id == 1)
            {
                $titleBn = $this->randomTitleBn();
            }else{
                $titleBn = null;
            }

            $book = Book::create([
                'title_en' => $title,
                'title_bn' => $titleBn, // Not all books have Bengali titles
                'category_id' => $category->id,
                'primary_author_id' => $primaryAuthor->id,
                'publisher_id' => $publisher->id,
                'language_id' => $language->id,
                'isbn' => $isbn,
                'publication_year' => rand(1950, (int)date('Y')),
                'pages' => rand(120, 900),
                'description_en' => 'Autogenerated demo book for system testing.',
                'description_bn' => 'বিহারের অরণ্য জীবনের অপূর্ব বর্ণনা। প্রকৃতি ও মানুষের সম্পর্কের মর্মস্পর্শী উপন্যাস।',
                'available_copies' => $totalCopies,
                'total_copies' => $totalCopies,
                'cover_path' => $coverPath,
            ]);

            $book->authors()->syncWithoutDetaching([$primaryAuthor->id]);
            $books->push($book);
        }

        return $books;
    }

    protected function generateLoanHistory(\Illuminate\Support\Collection $books, \Illuminate\Support\Collection $users): void
    {
        // Create a mix of returned, issued, pending, return_requested, declined
        $now = Carbon::now();

        foreach ($books as $book) {
            $historyCount = rand(0, 5); // past loans
            for ($h = 0; $h < $historyCount; $h++) {
                $user = $users->random();
                $issuedAt = $now->copy()->subDays(rand(30, 365));
                $dueAt = $issuedAt->copy()->addDays(rand(7, 30));
                $returnedAt = rand(0,1) ? $dueAt->copy()->subDays(rand(0, 5)) : $dueAt->copy()->addDays(rand(0, 10));

                Loan::create([
                    'book_id' => $book->id,
                    'user_id' => $user->id,
                    'issued_by_user_id' => null,
                    'requested_at' => $issuedAt->copy()->subDays(1)->toDateString(),
                    'requested_due_at' => null,
                    'issued_at' => $issuedAt->toDateString(),
                    'due_at' => $dueAt->toDateString(),
                    'returned_at' => $returnedAt->toDateString(),
                    'status' => 'returned',
                ]);
            }

            // Current state: zero or more active loans/requests
            $activeCount = rand(0, min(2, $book->total_copies));
            $currentIssued = 0;
            for ($a = 0; $a < $activeCount; $a++) {
                $user = $users->random();
                $r = rand(1, 100);
                if ($r <= 60) {
                    // issued (some overdue)
                    $issuedAt = $now->copy()->subDays(rand(0, 20));
                    $dueAt = $issuedAt->copy()->addDays(rand(7, 21));
                    $overdueAt = null;
                    if ($dueAt->lt($now)) { $overdueAt = $now->copy()->toDateString(); }
                    Loan::create([
                        'book_id' => $book->id,
                        'user_id' => $user->id,
                        'issued_by_user_id' => null,
                        'requested_at' => $issuedAt->copy()->subDays(1)->toDateString(),
                        'requested_due_at' => null,
                        'issued_at' => $issuedAt->toDateString(),
                        'due_at' => $dueAt->toDateString(),
                        'returned_at' => null,
                        'status' => 'issued',
                        'overdue_at' => $overdueAt,
                    ]);
                    $currentIssued++;
                } elseif ($r <= 85) {
                    // pending
                    Loan::create([
                        'book_id' => $book->id,
                        'user_id' => $user->id,
                        'issued_by_user_id' => null,
                        'requested_at' => $now->copy()->subDays(rand(0, 5))->toDateString(),
                        'requested_due_at' => null,
                        'issued_at' => null,
                        'due_at' => null,
                        'returned_at' => null,
                        'status' => 'pending',
                    ]);
                } else {
                    // return_requested (on an issued)
                    $issuedAt = $now->copy()->subDays(rand(1, 14));
                    $dueAt = $issuedAt->copy()->addDays(rand(7, 21));
                    Loan::create([
                        'book_id' => $book->id,
                        'user_id' => $user->id,
                        'issued_by_user_id' => null,
                        'requested_at' => $issuedAt->copy()->subDays(1)->toDateString(),
                        'requested_due_at' => null,
                        'issued_at' => $issuedAt->toDateString(),
                        'due_at' => $dueAt->toDateString(),
                        'returned_at' => null,
                        'status' => 'return_requested',
                    ]);
                    $currentIssued++;
                }
            }

            // Update availability based on issued count
            $available = max(0, $book->total_copies - $currentIssued);
            $book->update(['available_copies' => $available]);
        }
    }

    protected function randomTitle(): string
    {
        $adjectives = ['Silent','Hidden','Lost','Bright','Invisible','Crimson','Golden','Midnight','Distant','Broken','Sacred'];
        $nouns = ['Forest','River','Empire','Secret','Journey','Legacy','Dreams','Shadows','Voices','Horizons','Memories'];
        return $adjectives[array_rand($adjectives)].' '.
               $nouns[array_rand($nouns)].' #'.rand(1000, 99999);
    }
    protected function randomTitleBn(): string
    {
        $adjectives = ['সুন্দর','নির্দশিত','অনুপস্থিত','উজ্জ্বল','অদৃশ্য','ক্রিমসন','গোল্ডেন','মিডনাইট','দূরত্বপূর্ণ','বিচ্ছিন্ন','সাক্ষর'];
        $nouns = ['গাছ','নদী','ইম্পেরিয়া','গোপন','যোগাযোগ','অনুমতি','প্রকাশ','ছায়া','আলো','দূরত্ব','সাক্ষর'];
        return $adjectives[array_rand($adjectives)].' '.
               $nouns[array_rand($nouns)].' #'.rand(1000, 99999);
    }

    protected function fakeIsbn13(): string
    {
        $base = str_pad((string)rand(0, 999999999999), 12, '0', STR_PAD_LEFT);
        // Simple checksum (not strictly accurate but fine for demo)
        $sum = 0;
        for ($i=0; $i<12; $i++) { $sum += (int)$base[$i] * ($i % 2 === 0 ? 1 : 3); }
        $check = (10 - ($sum % 10)) % 10;
        return substr($base,0,3).'-'.substr($base,3,1).'-'.substr($base,4,4).'-'.substr($base,8,4).'-'.$check;
    }

    protected function downloadRandomCover(): ?string
    {
        try {
            $url = 'https://picsum.photos/seed/'.Str::random(12).'/400/600';
            $response = Http::timeout(10)->get($url);
            if (!$response->ok()) { return null; }
            $path = 'covers/'.Str::random(32).'.jpg';
            Storage::disk('public')->put($path, $response->body());
            return $path;
        } catch (\Throwable $e) {
            return null;
        }
    }
}


