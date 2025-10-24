<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\RecommendationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Search API
Route::get('/search/books', [SearchController::class, 'searchBooks']);

// Recommendation API - requires authentication
Route::middleware('auth:sanctum')->group(function () {
    // Get personalized book recommendations
    Route::get('/recommendations/books', [RecommendationController::class, 'getRecommendations']);
    
    // Get user preferences and reading statistics
    Route::get('/recommendations/preferences', [RecommendationController::class, 'getUserPreferences']);
    
    // Clear recommendation cache
    Route::delete('/recommendations/cache', [RecommendationController::class, 'clearCache']);
    
    // Get recommendation explanations (for debugging)
    Route::get('/recommendations/explanations', [RecommendationController::class, 'getRecommendationExplanations']);
});
