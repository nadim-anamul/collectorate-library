<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Models\Book;
use App\Models\Models\Category;
use App\Models\Models\Loan;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search'),
            'category' => $request->get('category'),
            'language' => $request->get('language'),
            'year' => $request->get('year'),
        ];

        $query = Book::with(['category','language','primaryAuthor','publisher']);
        if ($filters['search']) {
            $search = $filters['search'];
            $query->where(function($q) use ($search){
                $q->where('title_en','like',"%{$search}%")
                  ->orWhere('title_bn','like',"%{$search}%")
                  ->orWhere('isbn','like',"%{$search}%")
                  ->orWhereHas('primaryAuthor', function($aq) use ($search){
                      $aq->where('name_en','like',"%{$search}%")
                         ->orWhere('name_bn','like',"%{$search}%");
                  })
                  ->orWhereHas('publisher', function($pq) use ($search){
                      $pq->where('name_en','like',"%{$search}%")
                         ->orWhere('name_bn','like',"%{$search}%");
                  })
                  ->orWhereHas('category', function($cq) use ($search){
                      $cq->where('name_en','like',"%{$search}%")
                         ->orWhere('name_bn','like',"%{$search}%");
                  });
            });
        }
        if ($filters['category']) {
            $query->where('category_id', $filters['category']);
        }
        if ($filters['language']) {
            $query->whereHas('language', function($q) use ($filters){
                $q->where('code', $filters['language']);
            });
        }
        if ($filters['year']) {
            $query->where('publication_year', $filters['year']);
        }

        $books = $query->latest()->paginate(12)->appends($request->query());

        $stats = [
            'books' => Book::count(),
            'available_books' => Book::where('available_copies', '>', 0)->count(),
            'pending_loans' => Loan::where('status','pending')->count(),
            'users' => User::where('status','approved')->count(),
            'loans_active' => Loan::where('status','issued')->count(),
        ];

        $categories = Category::orderBy('name_en')->get();
        $languages = Language::orderBy('name')->pluck('code');
        $years = Book::select('publication_year')->distinct()->whereNotNull('publication_year')->orderBy('publication_year', 'desc')->pluck('publication_year');

        $activities = Activity::latest()->limit(10)->get();

        return view('admin.dashboard', compact('books', 'stats', 'categories', 'languages', 'years', 'filters', 'activities'));
    }
}
