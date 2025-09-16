<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Models\Book;
use App\Models\Models\Category;
use App\Models\Models\Member;
use App\Models\Models\Loan;
use App\Models\Activity;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'category' => $request->get('category'),
            'language' => $request->get('language'),
            'year' => $request->get('year'),
        ];

        $query = Book::query();
        if ($filters['category']) {
            $query->where('category_id', $filters['category']);
        }
        if ($filters['language']) {
            $query->where('language_primary', $filters['language']);
        }
        if ($filters['year']) {
            $query->where('publication_year', $filters['year']);
        }

        $books = $query->latest()->paginate(12)->appends($request->query());

        $stats = [
            'books' => Book::count(),
            'available_books' => Book::where('available_copies', '>', 0)->count(),
            'categories' => Category::count(),
            'members' => Member::count(),
            'loans_active' => Loan::whereNull('returned_at')->count(),
        ];

        $categories = Category::orderBy('name_en')->get();
        $languages = Book::select('language_primary')->distinct()->whereNotNull('language_primary')->pluck('language_primary');
        $years = Book::select('publication_year')->distinct()->whereNotNull('publication_year')->orderBy('publication_year', 'desc')->pluck('publication_year');

        $activities = Activity::latest()->limit(10)->get();

        return view('admin.dashboard', compact('books', 'stats', 'categories', 'languages', 'years', 'filters', 'activities'));
    }
}
