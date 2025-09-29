# Intelligent Book Recommendation API

## Overview

This API provides intelligent, personalized book recommendations based on individual user preferences and reading history. It uses multiple algorithms with weighted scoring to deliver highly relevant suggestions.

## Features

- **Multi-Algorithm Approach**: Combines 5 different recommendation algorithms
- **Personalized**: Based on individual user's reading history and preferences
- **Cached Results**: Optimized performance with intelligent caching
- **Multiple Endpoints**: Various ways to access recommendations
- **Detailed Analytics**: User preference analysis and recommendation explanations

## Authentication

All endpoints require authentication using Laravel Sanctum. Include the Bearer token in the Authorization header:

```
Authorization: Bearer {your-token}
```

## API Endpoints

### 1. Get Personalized Recommendations

**Endpoint**: `GET /api/recommendations/books`

**Description**: Get intelligent book recommendations based on user's reading history and preferences.

**Parameters**:
- `limit` (optional): Number of recommendations to return (1-50, default: 10)
- `use_cache` (optional): Whether to use cached results (boolean, default: true)
- `algorithm` (optional): Specific algorithm to use (`all`, `category`, `author`, `tag`, `collaborative`, `popular`, default: `all`)

**Example Request**:
```bash
curl -H "Authorization: Bearer {token}" \
     "https://your-domain.com/api/recommendations/books?limit=15&algorithm=all"
```

**Example Response**:
```json
{
    "success": true,
    "message": "Recommendations retrieved successfully",
    "data": {
        "recommendations": [
            {
                "id": 123,
                "title_en": "The Great Gatsby",
                "title_bn": "দ্য গ্রেট গ্যাটসবি",
                "isbn": "9780743273565",
                "publication_year": 1925,
                "pages": 180,
                "available_copies": 3,
                "total_copies": 5,
                "cover_url": "https://your-domain.com/storage/covers/gatsby.jpg",
                "category": {
                    "id": 5,
                    "name_en": "Fiction",
                    "name_bn": "কল্পকাহিনী"
                },
                "primary_author": {
                    "id": 42,
                    "name_en": "F. Scott Fitzgerald",
                    "name_bn": "এফ. স্কট ফিটজগারাল্ড"
                },
                "publisher": {
                    "id": 12,
                    "name_en": "Scribner",
                    "name_bn": "স্ক্রিবনার"
                },
                "language": {
                    "id": 1,
                    "name": "English",
                    "code": "en"
                },
                "tags": [
                    {
                        "id": 23,
                        "name": "Classic Literature"
                    },
                    {
                        "id": 45,
                        "name": "American Literature"
                    }
                ],
                "description_en": "A classic American novel...",
                "description_bn": "একটি ক্লাসিক আমেরিকান উপন্যাস..."
            }
        ],
        "total": 15,
        "algorithm_used": "all",
        "user_id": 1,
        "timestamp": "2024-01-15T10:30:00.000000Z"
    }
}
```

### 2. Get User Preferences

**Endpoint**: `GET /api/recommendations/preferences`

**Description**: Get detailed analysis of user's reading preferences and statistics.

**Example Request**:
```bash
curl -H "Authorization: Bearer {token}" \
     "https://your-domain.com/api/recommendations/preferences"
```

**Example Response**:
```json
{
    "success": true,
    "message": "User preferences retrieved successfully",
    "data": {
        "preferences": {
            "categories": {
                "5": 3,
                "12": 2,
                "8": 1
            },
            "authors": {
                "42": 2,
                "67": 1,
                "23": 1
            },
            "tags": {
                "23": 2,
                "45": 1,
                "78": 1
            },
            "languages": {
                "1": 4,
                "2": 2
            },
            "publishers": {
                "12": 2,
                "34": 1
            },
            "publication_years": {
                "2020": 2,
                "2019": 1,
                "2021": 1
            },
            "average_rating": 0,
            "total_books_read": 6
        },
        "user_id": 1,
        "timestamp": "2024-01-15T10:30:00.000000Z"
    }
}
```

### 3. Clear Recommendation Cache

**Endpoint**: `DELETE /api/recommendations/cache`

**Description**: Clear cached recommendations for the authenticated user.

**Example Request**:
```bash
curl -X DELETE \
     -H "Authorization: Bearer {token}" \
     "https://your-domain.com/api/recommendations/cache"
```

**Example Response**:
```json
{
    "success": true,
    "message": "Recommendation cache cleared successfully",
    "data": {
        "user_id": 1,
        "timestamp": "2024-01-15T10:30:00.000000Z"
    }
}
```

### 4. Get Recommendation Explanations

**Endpoint**: `GET /api/recommendations/explanations`

**Description**: Get detailed explanations of how recommendations were generated (useful for debugging and understanding the algorithm).

**Parameters**:
- `limit` (optional): Number of explanations to return (default: 5)

**Example Request**:
```bash
curl -H "Authorization: Bearer {token}" \
     "https://your-domain.com/api/recommendations/explanations?limit=10"
```

**Example Response**:
```json
{
    "success": true,
    "message": "Recommendation explanations retrieved successfully",
    "data": {
        "explanations": {
            "category": {
                "123": 0.8,
                "456": 0.6,
                "789": 0.4
            },
            "author": {
                "123": 0.9,
                "321": 0.7,
                "654": 0.5
            },
            "tag": {
                "123": 0.3,
                "456": 0.2
            },
            "collaborative": {
                "789": 0.6,
                "123": 0.4
            },
            "popular": {
                "456": 0.9,
                "321": 0.8,
                "123": 0.7
            },
            "user_preferences": {
                "total_books_read": 6,
                "top_categories": {
                    "5": 3,
                    "12": 2
                },
                "top_authors": {
                    "42": 2,
                    "67": 1
                },
                "top_tags": {
                    "23": 2,
                    "45": 1
                }
            }
        },
        "user_id": 1,
        "timestamp": "2024-01-15T10:30:00.000000Z"
    }
}
```

## Recommendation Algorithms

The system uses a weighted combination of 5 different algorithms:

### 1. Category-Based Recommendations (Weight: 30%)
- Analyzes user's preferred book categories
- Recommends books from the same categories
- Higher weight for categories the user has read more books from

### 2. Author-Based Recommendations (Weight: 25%)
- Identifies favorite authors based on reading history
- Recommends other books by the same authors
- Considers both primary and secondary authors

### 3. Tag-Based Recommendations (Weight: 20%)
- Uses book tags to find similar content
- Recommends books with overlapping tags
- More sophisticated than category matching

### 4. Collaborative Filtering (Weight: 15%)
- Finds users with similar reading patterns
- Recommends books that similar users enjoyed
- Uses category overlap to identify similar users

### 5. Popular Books (Weight: 10%)
- Recommends books that are frequently borrowed
- Provides fallback for new users or when other algorithms have limited data
- Based on overall library borrowing statistics

## Caching Strategy

- **Cache Duration**: 1 hour (3600 seconds)
- **Cache Keys**: Unique per user and algorithm
- **Cache Invalidation**: Automatic after 1 hour or manual via DELETE endpoint
- **Performance**: Significantly improves response times for repeated requests

## Error Handling

All endpoints return consistent error responses:

```json
{
    "success": false,
    "message": "Error description",
    "error": "Detailed error message (for debugging)",
    "errors": {
        "field": ["Validation error messages"]
    }
}
```

**Common HTTP Status Codes**:
- `200`: Success
- `401`: Unauthorized (missing or invalid token)
- `422`: Validation Error
- `500`: Server Error

## Usage Examples

### JavaScript/Frontend Integration

```javascript
// Get recommendations
async function getRecommendations(limit = 10) {
    const response = await fetch('/api/recommendations/books?limit=' + limit, {
        headers: {
            'Authorization': 'Bearer ' + userToken,
            'Accept': 'application/json',
        }
    });
    
    const data = await response.json();
    if (data.success) {
        return data.data.recommendations;
    } else {
        console.error('Failed to get recommendations:', data.message);
        return [];
    }
}

// Get specific algorithm recommendations
async function getCategoryRecommendations() {
    const response = await fetch('/api/recommendations/books?algorithm=category', {
        headers: {
            'Authorization': 'Bearer ' + userToken,
            'Accept': 'application/json',
        }
    });
    
    return await response.json();
}
```

### PHP/Backend Integration

```php
// In your controller
use App\Services\RecommendationService;

public function getRecommendations($userId, $limit = 10) {
    $user = User::find($userId);
    $recommendationService = new RecommendationService($user);
    
    return $recommendationService->getRecommendations($limit);
}
```

## Performance Considerations

- **Database Optimization**: Uses efficient queries with proper indexing
- **Caching**: Results are cached to reduce database load
- **Lazy Loading**: Related models are loaded efficiently
- **Query Limits**: Prevents excessive data retrieval

## Future Enhancements

Potential improvements for the recommendation system:

1. **Machine Learning**: Integrate ML models for better predictions
2. **Rating System**: Include user ratings in recommendations
3. **Time-Based**: Consider recency of reading preferences
4. **Seasonal**: Factor in seasonal reading patterns
5. **Social**: Include social recommendations from friends
6. **Content-Based**: Analyze book content for deeper similarity
7. **Hybrid Approach**: Combine multiple ML algorithms

## Testing

To test the API endpoints:

1. **Authentication**: Ensure you have a valid Sanctum token
2. **User Data**: Create some loan history for better recommendations
3. **Multiple Users**: Test with users having different reading patterns
4. **Cache Testing**: Test cache behavior by making repeated requests
5. **Error Scenarios**: Test with invalid parameters and unauthenticated requests

## Support

For issues or questions about the recommendation API, please check:
1. Laravel logs for detailed error messages
2. Cache configuration in your environment
3. Database indexes for optimal performance
4. User authentication and Sanctum configuration
