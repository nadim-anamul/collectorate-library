<?php

namespace App\Services;

use App\Models\Models\Book;
use App\Models\Models\Loan;
use App\Models\Models\Category;
use App\Models\Models\Tag;
use App\Models\Author;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class RecommendationService
{
    private $user;
    private $cachePrefix = 'recommendations';

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get personalized book recommendations for a user
     */
    public function getRecommendations($limit = 10, $useCache = true)
    {
        $cacheKey = $this->cachePrefix . '_user_' . $this->user->id . '_limit_' . $limit;
        
        if ($useCache && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Get user's reading history and preferences
        $userPreferences = $this->analyzeUserPreferences();
        $excludedBookIds = $this->getExcludedBookIds();
        
        // Apply multiple recommendation algorithms with weights
        $recommendations = $this->getWeightedRecommendations($userPreferences, $excludedBookIds, $limit);
        
        // Cache results for 1 hour
        Cache::put($cacheKey, $recommendations, 3600);
        
        return $recommendations;
    }

    /**
     * Analyze user's reading preferences based on history
     */
    private function analyzeUserPreferences()
    {
        $userLoans = Loan::where('user_id', $this->user->id)
            ->whereIn('status', ['issued', 'returned'])
            ->with(['book.category', 'book.primaryAuthor', 'book.tags', 'book.language'])
            ->get();

        $preferences = [
            'categories' => [],
            'authors' => [],
            'tags' => [],
            'languages' => [],
            'publishers' => [],
            'publication_years' => [],
            'average_rating' => 0,
            'total_books_read' => $userLoans->count()
        ];

        if ($userLoans->isEmpty()) {
            // New user - return popular books instead
            return $this->getPopularBooksPreferences();
        }

        // Analyze categories
        $categoryCounts = [];
        foreach ($userLoans as $loan) {
            if ($loan->book->category) {
                $categoryCounts[$loan->book->category->id] = 
                    ($categoryCounts[$loan->book->category->id] ?? 0) + 1;
            }
        }
        $preferences['categories'] = $categoryCounts;

        // Analyze authors
        $authorCounts = [];
        foreach ($userLoans as $loan) {
            if ($loan->book->primaryAuthor) {
                $authorCounts[$loan->book->primaryAuthor->id] = 
                    ($authorCounts[$loan->book->primaryAuthor->id] ?? 0) + 1;
            }
        }
        $preferences['authors'] = $authorCounts;

        // Analyze tags
        $tagCounts = [];
        foreach ($userLoans as $loan) {
            foreach ($loan->book->tags as $tag) {
                $tagCounts[$tag->id] = ($tagCounts[$tag->id] ?? 0) + 1;
            }
        }
        $preferences['tags'] = $tagCounts;

        // Analyze languages
        $languageCounts = [];
        foreach ($userLoans as $loan) {
            if ($loan->book->language) {
                $languageCounts[$loan->book->language->id] = 
                    ($languageCounts[$loan->book->language->id] ?? 0) + 1;
            }
        }
        $preferences['languages'] = $languageCounts;

        // Analyze publishers
        $publisherCounts = [];
        foreach ($userLoans as $loan) {
            if ($loan->book->publisher) {
                $publisherCounts[$loan->book->publisher->id] = 
                    ($publisherCounts[$loan->book->publisher->id] ?? 0) + 1;
            }
        }
        $preferences['publishers'] = $publisherCounts;

        // Analyze publication years
        $yearCounts = [];
        foreach ($userLoans as $loan) {
            if ($loan->book->publication_year) {
                $yearCounts[$loan->book->publication_year] = 
                    ($yearCounts[$loan->book->publication_year] ?? 0) + 1;
            }
        }
        $preferences['publication_years'] = $yearCounts;

        return $preferences;
    }

    /**
     * Get books user has already borrowed or currently has
     */
    private function getExcludedBookIds()
    {
        return Loan::where('user_id', $this->user->id)
            ->pluck('book_id')
            ->toArray();
    }

    /**
     * Get weighted recommendations using multiple algorithms
     */
    private function getWeightedRecommendations($preferences, $excludedBookIds, $limit)
    {
        $bookScores = [];

        // 1. Category-based recommendations (Weight: 30%)
        $categoryBooks = $this->getCategoryBasedRecommendations($preferences['categories'], $excludedBookIds);
        $this->addToBookScores($bookScores, $categoryBooks, 0.30);

        // 2. Author-based recommendations (Weight: 25%)
        $authorBooks = $this->getAuthorBasedRecommendations($preferences['authors'], $excludedBookIds);
        $this->addToBookScores($bookScores, $authorBooks, 0.25);

        // 3. Tag-based recommendations (Weight: 20%)
        $tagBooks = $this->getTagBasedRecommendations($preferences['tags'], $excludedBookIds);
        $this->addToBookScores($bookScores, $tagBooks, 0.20);

        // 4. Collaborative filtering (Weight: 15%)
        $collaborativeBooks = $this->getCollaborativeRecommendationsInternal($preferences, $excludedBookIds);
        $this->addToBookScores($bookScores, $collaborativeBooks, 0.15);

        // 5. Popular books (Weight: 10%)
        $popularBooks = $this->getPopularBooks($excludedBookIds);
        $this->addToBookScores($bookScores, $popularBooks, 0.10);

        // Sort by score and return top books
        arsort($bookScores);
        
        // If no recommendations found, return popular books as fallback
        if (empty($bookScores)) {
            return $this->getPopularBooksFallback($excludedBookIds, $limit);
        }
        
        $topBookIds = array_slice(array_keys($bookScores), 0, $limit * 2); // Get more for filtering
        
        return Book::whereIn('id', $topBookIds)
            ->where('available_copies', '>', 0)
            ->with(['category', 'tags', 'language', 'primaryAuthor', 'publisher'])
            ->get()
            ->sortByDesc(function ($book) use ($bookScores) {
                return $bookScores[$book->id] ?? 0;
            })
            ->take($limit)
            ->values();
    }

    /**
     * Add book scores to the main score array
     */
    private function addToBookScores(&$bookScores, $books, $weight)
    {
        if (empty($books)) {
            return;
        }
        
        $maxScore = max($books);
        if ($maxScore > 0) {
            foreach ($books as $bookId => $score) {
                $normalizedScore = $score / $maxScore;
                $bookScores[$bookId] = ($bookScores[$bookId] ?? 0) + ($normalizedScore * $weight);
            }
        }
    }

    /**
     * Category-based recommendations
     */
    private function getCategoryBasedRecommendations($categoryPreferences, $excludedBookIds)
    {
        if (empty($categoryPreferences)) {
            return [];
        }

        $categoryIds = array_keys($categoryPreferences);
        $categoryWeights = array_values($categoryPreferences);
        $maxWeight = !empty($categoryWeights) ? max($categoryWeights) : 0;

        $books = DB::table('books')
            ->whereIn('category_id', $categoryIds)
            ->whereNotIn('id', $excludedBookIds)
            ->where('available_copies', '>', 0)
            ->select('id', 'category_id')
            ->get();

        $bookScores = [];
        foreach ($books as $book) {
            $categoryWeight = $categoryPreferences[$book->category_id] ?? 0;
            $normalizedWeight = $categoryWeight / $maxWeight;
            $bookScores[$book->id] = $normalizedWeight;
        }

        return $bookScores;
    }

    /**
     * Author-based recommendations
     */
    private function getAuthorBasedRecommendations($authorPreferences, $excludedBookIds)
    {
        if (empty($authorPreferences)) {
            return [];
        }

        $authorIds = array_keys($authorPreferences);
        $authorWeights = array_values($authorPreferences);
        $maxWeight = !empty($authorWeights) ? max($authorWeights) : 0;

        $books = DB::table('books')
            ->whereIn('primary_author_id', $authorIds)
            ->whereNotIn('id', $excludedBookIds)
            ->where('available_copies', '>', 0)
            ->select('id', 'primary_author_id')
            ->get();

        $bookScores = [];
        foreach ($books as $book) {
            $authorWeight = $authorPreferences[$book->primary_author_id] ?? 0;
            $normalizedWeight = $authorWeight / $maxWeight;
            $bookScores[$book->id] = $normalizedWeight;
        }

        return $bookScores;
    }

    /**
     * Tag-based recommendations
     */
    private function getTagBasedRecommendations($tagPreferences, $excludedBookIds)
    {
        if (empty($tagPreferences)) {
            return [];
        }

        $tagIds = array_keys($tagPreferences);
        $tagWeights = array_values($tagPreferences);
        $maxWeight = !empty($tagWeights) ? max($tagWeights) : 0;

        $books = DB::table('books')
            ->join('book_tag', 'books.id', '=', 'book_tag.book_id')
            ->whereIn('book_tag.tag_id', $tagIds)
            ->whereNotIn('books.id', $excludedBookIds)
            ->where('books.available_copies', '>', 0)
            ->select('books.id', 'book_tag.tag_id')
            ->get();

        $bookScores = [];
        foreach ($books as $book) {
            $tagWeight = $tagPreferences[$book->tag_id] ?? 0;
            $normalizedWeight = $tagWeight / $maxWeight;
            $bookScores[$book->id] = ($bookScores[$book->id] ?? 0) + $normalizedWeight;
        }

        return $bookScores;
    }

    /**
     * Collaborative filtering - find users with similar reading patterns
     */
    private function getCollaborativeRecommendationsInternal($userPreferences, $excludedBookIds)
    {
        // Find users who have read books in similar categories
        $userCategoryIds = array_keys($userPreferences['categories']);
        
        if (empty($userCategoryIds)) {
            return [];
        }

        $similarUsers = DB::table('loans')
            ->join('books', 'loans.book_id', '=', 'books.id')
            ->where('loans.user_id', '!=', $this->user->id)
            ->whereIn('books.category_id', $userCategoryIds)
            ->whereIn('loans.status', ['issued', 'returned'])
            ->select('loans.user_id', 'loans.book_id', 'books.category_id')
            ->groupBy('loans.user_id', 'loans.book_id', 'books.category_id')
            ->havingRaw('COUNT(*) >= 2') // Users with at least 2 books in common categories
            ->get()
            ->groupBy('user_id');

        if ($similarUsers->isEmpty()) {
            return [];
        }

        $bookScores = [];
        foreach ($similarUsers as $userId => $userBooks) {
            $userBookIds = $userBooks->pluck('book_id')->toArray();
            
            // Get books this similar user liked that current user hasn't read
            $recommendedBooks = DB::table('loans')
                ->where('user_id', $userId)
                ->whereIn('status', ['issued', 'returned'])
                ->whereNotIn('book_id', $excludedBookIds)
                ->pluck('book_id');

            foreach ($recommendedBooks as $bookId) {
                $bookScores[$bookId] = ($bookScores[$bookId] ?? 0) + 1;
            }
        }

        return $bookScores;
    }

    /**
     * Get popular books (most borrowed books)
     */
    private function getPopularBooks($excludedBookIds)
    {
        $popularBooks = DB::table('loans')
            ->whereIn('status', ['issued', 'returned'])
            ->whereNotIn('book_id', $excludedBookIds)
            ->select('book_id', DB::raw('COUNT(*) as borrow_count'))
            ->groupBy('book_id')
            ->orderBy('borrow_count', 'desc')
            ->limit(50)
            ->get();

        $bookScores = [];
        $maxBorrows = $popularBooks->isNotEmpty() ? $popularBooks->max('borrow_count') : 0;
        
        if ($maxBorrows > 0) {
            foreach ($popularBooks as $book) {
                $bookScores[$book->book_id] = $book->borrow_count / $maxBorrows;
            }
        }

        return $bookScores;
    }

    /**
     * Get popular books preferences for new users
     */
    private function getPopularBooksPreferences()
    {
        $popularCategories = DB::table('loans')
            ->join('books', 'loans.book_id', '=', 'books.id')
            ->whereIn('loans.status', ['issued', 'returned'])
            ->select('books.category_id', DB::raw('COUNT(*) as count'))
            ->groupBy('books.category_id')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->pluck('category_id')
            ->toArray();

        return [
            'categories' => array_flip($popularCategories),
            'authors' => [],
            'tags' => [],
            'languages' => [],
            'publishers' => [],
            'publication_years' => [],
            'average_rating' => 0,
            'total_books_read' => 0
        ];
    }

    /**
     * Get user preferences for API
     */
    public function getUserPreferences()
    {
        return $this->analyzeUserPreferences();
    }

    /**
     * Get category-based recommendations only
     */
    public function getCategoryRecommendations($limit = 10, $useCache = true)
    {
        $cacheKey = $this->cachePrefix . '_category_user_' . $this->user->id . '_limit_' . $limit;
        
        if ($useCache && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $preferences = $this->analyzeUserPreferences();
        $excludedBookIds = $this->getExcludedBookIds();
        
        $categoryBooks = $this->getCategoryBasedRecommendations($preferences['categories'], $excludedBookIds);
        
        if (empty($categoryBooks)) {
            return $this->getPopularBooksFallback($excludedBookIds, $limit);
        }
        
        $topBookIds = array_slice(array_keys($categoryBooks), 0, $limit);
        
        $recommendations = Book::whereIn('id', $topBookIds)
            ->where('available_copies', '>', 0)
            ->with(['category', 'tags', 'language', 'primaryAuthor', 'publisher'])
            ->get()
            ->sortByDesc(function ($book) use ($categoryBooks) {
                return $categoryBooks[$book->id] ?? 0;
            })
            ->values();

        Cache::put($cacheKey, $recommendations, 3600);
        return $recommendations;
    }

    /**
     * Get author-based recommendations only
     */
    public function getAuthorRecommendations($limit = 10, $useCache = true)
    {
        $cacheKey = $this->cachePrefix . '_author_user_' . $this->user->id . '_limit_' . $limit;
        
        if ($useCache && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $preferences = $this->analyzeUserPreferences();
        $excludedBookIds = $this->getExcludedBookIds();
        
        $authorBooks = $this->getAuthorBasedRecommendations($preferences['authors'], $excludedBookIds);
        
        if (empty($authorBooks)) {
            return $this->getPopularBooksFallback($excludedBookIds, $limit);
        }
        
        $topBookIds = array_slice(array_keys($authorBooks), 0, $limit);
        
        $recommendations = Book::whereIn('id', $topBookIds)
            ->where('available_copies', '>', 0)
            ->with(['category', 'tags', 'language', 'primaryAuthor', 'publisher'])
            ->get()
            ->sortByDesc(function ($book) use ($authorBooks) {
                return $authorBooks[$book->id] ?? 0;
            })
            ->values();

        Cache::put($cacheKey, $recommendations, 3600);
        return $recommendations;
    }

    /**
     * Get tag-based recommendations only
     */
    public function getTagRecommendations($limit = 10, $useCache = true)
    {
        $cacheKey = $this->cachePrefix . '_tag_user_' . $this->user->id . '_limit_' . $limit;
        
        if ($useCache && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $preferences = $this->analyzeUserPreferences();
        $excludedBookIds = $this->getExcludedBookIds();
        
        $tagBooks = $this->getTagBasedRecommendations($preferences['tags'], $excludedBookIds);
        
        if (empty($tagBooks)) {
            return $this->getPopularBooksFallback($excludedBookIds, $limit);
        }
        
        $topBookIds = array_slice(array_keys($tagBooks), 0, $limit);
        
        $recommendations = Book::whereIn('id', $topBookIds)
            ->where('available_copies', '>', 0)
            ->with(['category', 'tags', 'language', 'primaryAuthor', 'publisher'])
            ->get()
            ->sortByDesc(function ($book) use ($tagBooks) {
                return $tagBooks[$book->id] ?? 0;
            })
            ->values();

        Cache::put($cacheKey, $recommendations, 3600);
        return $recommendations;
    }

    /**
     * Get collaborative filtering recommendations only
     */
    public function getCollaborativeRecommendations($limit = 10, $useCache = true)
    {
        $cacheKey = $this->cachePrefix . '_collaborative_user_' . $this->user->id . '_limit_' . $limit;
        
        if ($useCache && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $preferences = $this->analyzeUserPreferences();
        $excludedBookIds = $this->getExcludedBookIds();
        
        $collaborativeBooks = $this->getCollaborativeRecommendationsInternal($preferences, $excludedBookIds);
        
        if (empty($collaborativeBooks)) {
            return $this->getPopularBooksFallback($excludedBookIds, $limit);
        }
        
        $topBookIds = array_slice(array_keys($collaborativeBooks), 0, $limit);
        
        $recommendations = Book::whereIn('id', $topBookIds)
            ->where('available_copies', '>', 0)
            ->with(['category', 'tags', 'language', 'primaryAuthor', 'publisher'])
            ->get()
            ->sortByDesc(function ($book) use ($collaborativeBooks) {
                return $collaborativeBooks[$book->id] ?? 0;
            })
            ->values();

        Cache::put($cacheKey, $recommendations, 3600);
        return $recommendations;
    }

    /**
     * Get popular books recommendations only
     */
    public function getPopularRecommendations($limit = 10, $useCache = true)
    {
        $cacheKey = $this->cachePrefix . '_popular_user_' . $this->user->id . '_limit_' . $limit;
        
        if ($useCache && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $excludedBookIds = $this->getExcludedBookIds();
        
        $popularBooks = $this->getPopularBooks($excludedBookIds);
        
        if (empty($popularBooks)) {
            return $this->getPopularBooksFallback($excludedBookIds, $limit);
        }
        
        $topBookIds = array_slice(array_keys($popularBooks), 0, $limit);
        
        $recommendations = Book::whereIn('id', $topBookIds)
            ->where('available_copies', '>', 0)
            ->with(['category', 'tags', 'language', 'primaryAuthor', 'publisher'])
            ->get()
            ->sortByDesc(function ($book) use ($popularBooks) {
                return $popularBooks[$book->id] ?? 0;
            })
            ->values();

        Cache::put($cacheKey, $recommendations, 3600);
        return $recommendations;
    }

    /**
     * Get recommendation explanations for debugging
     */
    public function getRecommendationExplanations($limit = 5)
    {
        $preferences = $this->analyzeUserPreferences();
        $excludedBookIds = $this->getExcludedBookIds();
        
        $explanations = [];
        
        // Get explanations for each algorithm
        $explanations['category'] = $this->getCategoryBasedRecommendations($preferences['categories'], $excludedBookIds);
        $explanations['author'] = $this->getAuthorBasedRecommendations($preferences['authors'], $excludedBookIds);
        $explanations['tag'] = $this->getTagBasedRecommendations($preferences['tags'], $excludedBookIds);
        $explanations['collaborative'] = $this->getCollaborativeRecommendationsInternal($preferences, $excludedBookIds);
        $explanations['popular'] = $this->getPopularBooks($excludedBookIds);
        
        // Get user preferences summary
        $explanations['user_preferences'] = [
            'total_books_read' => $preferences['total_books_read'],
            'top_categories' => array_slice($preferences['categories'], 0, 5, true),
            'top_authors' => array_slice($preferences['authors'], 0, 5, true),
            'top_tags' => array_slice($preferences['tags'], 0, 5, true),
        ];
        
        return $explanations;
    }

    /**
     * Fallback method to get popular books when no recommendations are found
     */
    private function getPopularBooksFallback($excludedBookIds, $limit)
    {
        return Book::whereNotIn('id', $excludedBookIds)
            ->where('available_copies', '>', 0)
            ->with(['category', 'tags', 'language', 'primaryAuthor', 'publisher'])
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Clear recommendation cache for a user
     */
    public function clearCache()
    {
        $pattern = $this->cachePrefix . '_user_' . $this->user->id . '_*';
        // Note: Laravel cache doesn't have a direct pattern delete, 
        // so we'll implement a simple version
        Cache::forget($this->cachePrefix . '_user_' . $this->user->id . '_limit_10');
        Cache::forget($this->cachePrefix . '_user_' . $this->user->id . '_limit_20');
        Cache::forget($this->cachePrefix . '_category_user_' . $this->user->id . '_limit_10');
        Cache::forget($this->cachePrefix . '_author_user_' . $this->user->id . '_limit_10');
        Cache::forget($this->cachePrefix . '_tag_user_' . $this->user->id . '_limit_10');
        Cache::forget($this->cachePrefix . '_collaborative_user_' . $this->user->id . '_limit_10');
        Cache::forget($this->cachePrefix . '_popular_user_' . $this->user->id . '_limit_10');
    }
}
