<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Models\Loan;
use App\Models\Models\Book;
use App\Models\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->get('range','monthly');
        $start = match($range){
            'daily' => Carbon::now()->startOfDay(),
            'weekly' => Carbon::now()->startOfWeek(),
            'monthly' => Carbon::now()->startOfMonth(),
            default => Carbon::now()->startOfMonth(),
        };

        $issuedCount = Loan::where('issued_at','>=',$start->toDateString())->count();
        $lateLoans = Loan::whereNotNull('returned_at')->where('late_fee','>',0)->with('user')->latest()->limit(20)->get();
        $popularBooks = Loan::select('book_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('book_id')->orderByDesc('cnt')->with('book')->limit(10)->get();
        $categoryCounts = Book::select('category_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('category_id')->with('category')->get();

        return view('admin.reports.index', compact('range','issuedCount','lateLoans','popularBooks','categoryCounts'));
    }
}
