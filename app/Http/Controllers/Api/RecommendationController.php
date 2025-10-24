<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RecommendationController extends Controller
{
    /**
     * Get personalized book recommendations for authenticated user
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getRecommendations(Request $request): JsonResponse
    {
        try {
            // Validate request parameters
            $validator = Validator::make($request->all(), [
                'limit' => 'integer|min:1|max:50',
                'use_cache' => 'boolean',
                'algorithm' => 'string|in:all,category,author,tag,collaborative,popular'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $limit = $request->get('limit', 10);
            $useCache = $request->get('use_cache', true);
            $algorithm = $request->get('algorithm', 'all');

            $recommendationService = new RecommendationService($user);
            
            // Get recommendations based on algorithm preference
            $recommendations = $this->getRecommendationsByAlgorithm(
                $recommendationService, 
                $algorithm, 
                $limit, 
                $useCache
            );

            // Transform recommendations for API response
            $transformedRecommendations = $recommendations->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title_en' => $book->title_en,
                    'title_bn' => $book->title_bn,
                    'isbn' => $book->isbn,
                    'publication_year' => $book->publication_year,
                    'pages' => $book->pages,
                    'available_copies' => $book->available_copies,
                    'total_copies' => $book->total_copies,
                    'cover_url' => $book->cover_path ? asset('storage/' . $book->cover_path) : null,
                    'category' => $book->category ? [
                        'id' => $book->category->id,
                        'name_en' => $book->category->name_en,
                        'name_bn' => $book->category->name_bn,
                    ] : null,
                    'primary_author' => $book->primaryAuthor ? [
                        'id' => $book->primaryAuthor->id,
                        'name_en' => $book->primaryAuthor->name_en,
                        'name_bn' => $book->primaryAuthor->name_bn,
                    ] : null,
                    'publisher' => $book->publisher ? [
                        'id' => $book->publisher->id,
                        'name_en' => $book->publisher->name_en,
                        'name_bn' => $book->publisher->name_bn,
                    ] : null,
                    'language' => $book->language ? [
                        'id' => $book->language->id,
                        'name' => $book->language->name,
                        'code' => $book->language->code,
                    ] : null,
                    'tags' => $book->tags->map(function ($tag) {
                        return [
                            'id' => $tag->id,
                            'name' => $tag->name,
                        ];
                    }),
                    'description_en' => $book->description_en,
                    'description_bn' => $book->description_bn,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Recommendations retrieved successfully',
                'data' => [
                    'recommendations' => $transformedRecommendations,
                    'total' => $transformedRecommendations->count(),
                    'algorithm_used' => $algorithm,
                    'user_id' => $user->id,
                    'timestamp' => now()->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve recommendations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recommendations by specific algorithm
     */
    private function getRecommendationsByAlgorithm(
        RecommendationService $service, 
        string $algorithm, 
        int $limit, 
        bool $useCache
    ) {
        switch ($algorithm) {
            case 'category':
                return $service->getCategoryRecommendations($limit, $useCache);
            case 'author':
                return $service->getAuthorRecommendations($limit, $useCache);
            case 'tag':
                return $service->getTagRecommendations($limit, $useCache);
            case 'collaborative':
                return $service->getCollaborativeRecommendations($limit, $useCache);
            case 'popular':
                return $service->getPopularRecommendations($limit, $useCache);
            default:
                return $service->getRecommendations($limit, $useCache);
        }
    }

    /**
     * Get user's reading preferences and statistics
     * 
     * @return JsonResponse
     */
    public function getUserPreferences(): JsonResponse
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $recommendationService = new RecommendationService($user);
            $preferences = $recommendationService->getUserPreferences();

            return response()->json([
                'success' => true,
                'message' => 'User preferences retrieved successfully',
                'data' => [
                    'preferences' => $preferences,
                    'user_id' => $user->id,
                    'timestamp' => now()->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user preferences',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear recommendation cache for user
     * 
     * @return JsonResponse
     */
    public function clearCache(): JsonResponse
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $recommendationService = new RecommendationService($user);
            $recommendationService->clearCache();

            return response()->json([
                'success' => true,
                'message' => 'Recommendation cache cleared successfully',
                'data' => [
                    'user_id' => $user->id,
                    'timestamp' => now()->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear recommendation cache',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recommendation explanations for debugging
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getRecommendationExplanations(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $limit = $request->get('limit', 5);
            $recommendationService = new RecommendationService($user);
            
            // Get detailed explanations
            $explanations = $recommendationService->getRecommendationExplanations($limit);

            return response()->json([
                'success' => true,
                'message' => 'Recommendation explanations retrieved successfully',
                'data' => [
                    'explanations' => $explanations,
                    'user_id' => $user->id,
                    'timestamp' => now()->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve recommendation explanations',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
