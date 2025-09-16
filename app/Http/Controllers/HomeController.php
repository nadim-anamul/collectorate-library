<?php

namespace App\Http\Controllers;

use App\Models\Models\Book;
use App\Models\Models\Category;
use App\Models\Models\Tag;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with(['category', 'tags'])->where('available_copies', '>', 0);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title_en', 'like', '%'.$search.'%')
                  ->orWhere('title_bn', 'like', '%'.$search.'%')
                  ->orWhere('author_en', 'like', '%'.$search.'%')
                  ->orWhere('author_bn', 'like', '%'.$search.'%')
                  ->orWhere('publisher_en', 'like', '%'.$search.'%')
                  ->orWhere('isbn', 'like', '%'.$search.'%');
            });
        }
        
        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        // Language filter
        if ($request->filled('language')) {
            $query->where('language_primary', $request->language);
        }
        
        // Year filter
        if ($request->filled('year')) {
            $query->where('publication_year', $request->year);
        }
        
        // Sort options
        $sort = $request->get('sort', 'latest');
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
        
        $books = $query->paginate(12)->appends($request->query());
        
        // Get filter data
        $categories = Category::orderBy('name_en')->get();
        $languages = Book::select('language_primary')->distinct()->whereNotNull('language_primary')->pluck('language_primary');
        $years = Book::select('publication_year')->distinct()->whereNotNull('publication_year')->orderBy('publication_year', 'desc')->pluck('publication_year');
        
        return view('home', compact('books', 'categories', 'languages', 'years'));
    }

    public function show(Book $book)
    {
        $book->load(['primaryAuthor', 'publisher', 'category', 'language', 'tags', 'authors']);
        
        return view('books.show', compact('book'));
    }
}
