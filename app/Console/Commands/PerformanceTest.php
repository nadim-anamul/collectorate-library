<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Models\Book;
use Illuminate\Support\Facades\DB;

class PerformanceTest extends Command
{
    protected $signature = 'performance:test {--books=1000 : Number of books to simulate}';
    protected $description = 'Test database performance with simulated book count';

    public function handle()
    {
        $bookCount = $this->option('books');
        $this->info("Testing performance with {$bookCount} books simulation...");

        // Test 1: Basic query performance
        $this->testBasicQuery();
        
        // Test 2: Search performance
        $this->testSearchQuery();
        
        // Test 3: Filter performance
        $this->testFilterQuery();
        
        // Test 4: Pagination performance
        $this->testPaginationQuery();
        
        $this->info('Performance test completed!');
    }

    private function testBasicQuery()
    {
        $this->info('Testing basic query...');
        $start = microtime(true);
        
        $books = Book::with(['category', 'tags', 'language', 'primaryAuthor', 'publisher'])
            ->where('available_copies', '>', 0)
            ->paginate(15);
            
        $end = microtime(true);
        $time = round(($end - $start) * 1000, 2);
        
        $this->line("Basic query: {$time}ms ({$books->total()} books)");
    }

    private function testSearchQuery()
    {
        $this->info('Testing search query...');
        $start = microtime(true);
        
        $books = Book::with(['category', 'tags', 'language', 'primaryAuthor', 'publisher'])
            ->where('available_copies', '>', 0)
            ->where(function($q) {
                $q->where('title_en', 'like', '%test%')
                  ->orWhere('title_bn', 'like', '%test%')
                  ->orWhere('author_en', 'like', '%test%');
            })
            ->paginate(15);
            
        $end = microtime(true);
        $time = round(($end - $start) * 1000, 2);
        
        $this->line("Search query: {$time}ms ({$books->total()} results)");
    }

    private function testFilterQuery()
    {
        $this->info('Testing filter query...');
        $start = microtime(true);
        
        $books = Book::with(['category', 'tags', 'language', 'primaryAuthor', 'publisher'])
            ->where('available_copies', '>', 0)
            ->whereHas('category', function($q) {
                $q->where('id', 1);
            })
            ->paginate(15);
            
        $end = microtime(true);
        $time = round(($end - $start) * 1000, 2);
        
        $this->line("Filter query: {$time}ms ({$books->total()} results)");
    }

    private function testPaginationQuery()
    {
        $this->info('Testing pagination query...');
        $start = microtime(true);
        
        $books = Book::with(['category', 'tags', 'language', 'primaryAuthor', 'publisher'])
            ->where('available_copies', '>', 0)
            ->orderBy('available_copies', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $end = microtime(true);
        $time = round(($end - $start) * 1000, 2);
        
        $this->line("Pagination query: {$time}ms ({$books->total()} books)");
    }
}