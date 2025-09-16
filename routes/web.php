<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\ReportsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function(){ return redirect()->route('dashboard'); });

Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['role:Admin|Librarian'])->prefix('admin')->name('admin.')->group(function(){
        Route::get('/', [DashboardController::class, 'index'])->name('home');
        Route::resource('books', BookController::class);
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('tags', TagController::class)->except(['show']);
        Route::resource('members', MemberController::class)->except(['show']);
        Route::get('members/{member}/card', [MemberController::class,'card'])->name('members.card');

        Route::get('loans', [LoanController::class,'index'])->name('loans.index');
        Route::get('loans/create', [LoanController::class,'create'])->name('loans.create');
        Route::post('loans', [LoanController::class,'store'])->name('loans.store');
        Route::post('loans/{loan}/return', [LoanController::class,'return'])->name('loans.return');

        Route::get('reports', [ReportsController::class,'index'])->name('reports.index');
    });

    Route::get('/suggest/books', [SearchController::class,'suggestBooks'])->name('suggest.books');
});

require __DIR__.'/auth.php';
