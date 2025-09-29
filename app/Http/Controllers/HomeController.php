<?php

namespace App\Http\Controllers;

use App\Models\Models\Book;
use App\Models\Models\Category;
use App\Models\Models\Tag;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Base query: show all books by default (both available and unavailable)
        $query = Book::with(['category', 'tags', 'language', 'primaryAuthor', 'publisher']);
        
        // Optimized search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Direct field searches (fastest)
                $q->where('title_en', 'like', '%'.$search.'%')
                  ->orWhere('title_bn', 'like', '%'.$search.'%')
                  ->orWhere('author_en', 'like', '%'.$search.'%')
                  ->orWhere('author_bn', 'like', '%'.$search.'%')
                  ->orWhere('publisher_en', 'like', '%'.$search.'%')
                  ->orWhere('isbn', 'like', '%'.$search.'%');
                
                // Only search descriptions if search term is longer (performance optimization)
                if (strlen($search) > 3) {
                    $q->orWhere('description_en', 'like', '%'.$search.'%')
                      ->orWhere('description_bn', 'like', '%'.$search.'%');
                }
                
                // Related model searches (slower, so limit them)
                $q->orWhereHas('primaryAuthor', function ($subQ) use ($search) {
                      $subQ->where('name_en', 'like', '%'.$search.'%')
                           ->orWhere('name_bn', 'like', '%'.$search.'%');
                  })
                  ->orWhereHas('publisher', function ($subQ) use ($search) {
                      $subQ->where('name_en', 'like', '%'.$search.'%')
                           ->orWhere('name_bn', 'like', '%'.$search.'%');
                  })
                  ->orWhereHas('category', function ($subQ) use ($search) {
                      $subQ->where('name_en', 'like', '%'.$search.'%')
                           ->orWhere('name_bn', 'like', '%'.$search.'%');
                  })
                  ->orWhereHas('tags', function ($subQ) use ($search) {
                      $subQ->where('name', 'like', '%'.$search.'%');
                  });
            });
        }
        
        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        // Language filter
        if ($request->filled('language')) {
            $query->whereHas('language', function($q) use ($request) {
                $q->where('code', $request->language);
            });
        }
        
        // Year filter
        if ($request->filled('year')) {
            $query->where('publication_year', $request->year);
        }
        
        // Availability filter (simple)
        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->where('available_copies', '>', 0);
            } elseif ($request->availability === 'unavailable') {
                $query->where('available_copies', '=', 0);
            }
        }
        
        // Sort options
        $sort = $request->get('sort', 'latest');
        
        // Sort options
        // Unified sorting (no availability bias by default)
        switch ($sort) {
            case 'title':
                $query->orderBy('title_en');
                break;
            case 'author':
                $query->orderBy('author_en');
                break;
            case 'year':
                $query->orderBy('publication_year', 'desc');
                break;
            default:
                $query->latest();
        }

        // Logged-in users: prioritize recommended books first (primary ordering)
        if (Auth::check()) {
            $serviceForOrder = new RecommendationService(Auth::user());
            $recommendedForOrder = $serviceForOrder->getRecommendations(200)->pluck('id')->toArray();
            if (!empty($recommendedForOrder)) {
                $idsCsv = implode(',', $recommendedForOrder);
                // Bring recommended IDs to the top while preserving secondary ordering above
                $query->orderByRaw("FIELD(books.id, $idsCsv) DESC");
            }
        }
        
        $books = $query->paginate(15)->appends($request->query());

        // Recommendations for logged-in users (hero slider)
        $recommendedBooks = collect();
        if (Auth::check()) {
            $service = new RecommendationService(Auth::user());
            $recommendedBooks = $service->getRecommendations(24);
        }
        
        // Get filter data with caching
        $categories = cache()->remember('categories_list', 3600, function () {
            return Category::orderBy('name_en')->get();
        });
        
        $languages = cache()->remember('languages_list', 3600, function () {
            return \App\Models\Language::orderBy('name')->get();
        });
        
        $years = cache()->remember('publication_years_list', 1800, function () {
            return Book::select('publication_year')
                ->distinct()
                ->whereNotNull('publication_year')
                ->orderBy('publication_year', 'desc')
                ->pluck('publication_year');
        });
        
        // Get current filter values for display
        $currentFilters = [
            'category' => $request->filled('category') ? Category::find($request->category) : null,
            'language' => $request->filled('language') ? $request->language : null,
            'year' => $request->filled('year') ? $request->year : null,
            'availability' => $request->filled('availability') ? $request->availability : null,
            'sort' => $request->get('sort', 'latest'),
            'search' => $request->get('search'),
        ];
        
        return view('home', compact('books', 'categories', 'languages', 'years', 'currentFilters', 'recommendedBooks'));
    }

    public function show(Book $book)
    {
        $book->load(['primaryAuthor', 'publisher', 'category', 'language', 'tags', 'authors']);
        
        return view('books.show', compact('book'));
    }
}
