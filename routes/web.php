<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\ReservationsController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;

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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/books/{book}', [HomeController::class, 'show'])->name('books.show');

Route::middleware(['auth', 'approved'])->group(function(){
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/history', [MemberDashboardController::class, 'history'])->name('dashboard.history');

    // Borrow request from book page
    Route::post('/books/{book}/request', [LoanController::class,'request'])->name('books.request');
    Route::post('/loans/{loan}/request-return', [LoanController::class,'requestReturn'])->name('loans.requestReturn');
    Route::post('/books/{book}/reserve', [ReservationController::class,'store'])->name('books.reserve');
    // Notifications
    Route::get('/notifications', [NotificationController::class,'index'])->name('notifications.index');
    Route::get('/notifications/all', [NotificationController::class,'all'])->name('notifications.all');
    Route::post('/notifications/read', [NotificationController::class,'markAsRead'])->name('notifications.read');

    // Profile
    Route::get('/profile', [ProfileController::class,'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class,'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class,'update'])->name('profile.update');

    Route::middleware(['role:Admin|Librarian'])->prefix('admin')->name('admin.')->group(function(){
        Route::get('/', [DashboardController::class, 'index'])->name('home');
        Route::resource('books', BookController::class);
        Route::resource('authors', AuthorController::class)->except(['show']);
        Route::resource('publishers', PublisherController::class)->except(['show']);
        Route::resource('languages', LanguageController::class)->except(['show']);
        Route::resource('categories', CategoryController::class)->except(['show']);
        
        Route::get('users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('users/{user}', [UserManagementController::class, 'show'])->name('users.show');
        Route::post('users/{user}/approve', [UserManagementController::class, 'approve'])->name('users.approve');
        Route::post('users/{user}/reject', [UserManagementController::class, 'reject'])->name('users.reject');
        Route::put('users/{user}/role', [UserManagementController::class, 'updateRole'])->name('users.updateRole');
        Route::delete('users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
        Route::resource('tags', TagController::class)->except(['show']);

        Route::get('loans', [LoanController::class,'index'])->name('loans.index');
        Route::get('loans/{loan}', [LoanController::class,'show'])->name('loans.show');
        Route::get('loans/create', [LoanController::class,'create'])->name('loans.create');
        Route::post('loans', [LoanController::class,'store'])->name('loans.store');
        Route::post('loans/{loan}/return', [LoanController::class,'return'])->name('loans.return');
        Route::post('loans/{loan}/approve', [LoanController::class,'approve'])->name('loans.approve');
        Route::post('loans/{loan}/decline', [LoanController::class,'decline'])->name('loans.decline');

        Route::get('reports', [ReportsController::class,'index'])->name('reports.index');
        Route::get('reservations', [ReservationsController::class,'index'])->name('reservations.index');
    });

    Route::get('/suggest/books', [SearchController::class,'suggestBooks'])->name('suggest.books');
});

require __DIR__.'/auth.php';
