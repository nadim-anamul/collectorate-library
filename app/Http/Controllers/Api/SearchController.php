<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SearchController extends Controller
{
    public function searchBooks(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['books' => []]);
        }

        // Start with a comprehensive search including related models
        $books = Book::with(['primaryAuthor', 'publisher', 'category', 'language', 'tags'])
            ->where(function ($q) use ($query) {
                $q->where('title_en', 'like', "%{$query}%")
                  ->orWhere('title_bn', 'like', "%{$query}%")
                  ->orWhere('isbn', 'like', "%{$query}%")
                  ->orWhere('description_en', 'like', "%{$query}%")
                  ->orWhere('description_bn', 'like', "%{$query}%")
                  ->orWhereHas('primaryAuthor', function ($subQ) use ($query) {
                      $subQ->where('name_en', 'like', "%{$query}%")
                           ->orWhere('name_bn', 'like', "%{$query}%");
                  })
                  ->orWhereHas('publisher', function ($subQ) use ($query) {
                      $subQ->where('name_en', 'like', "%{$query}%")
                           ->orWhere('name_bn', 'like', "%{$query}%");
                  })
                  ->orWhereHas('category', function ($subQ) use ($query) {
                      $subQ->where('name_en', 'like', "%{$query}%")
                           ->orWhere('name_bn', 'like', "%{$query}%");
                  })
                  ->orWhereHas('tags', function ($subQ) use ($query) {
                      $subQ->where('name', 'like', "%{$query}%");
                  });
            })
            ->orderBy('title_en')
            ->limit(20)
            ->get()
            ->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->language_primary === 'bangla' && $book->title_bn ? $book->title_bn : ($book->title_en ?: $book->title_bn),
                    'title_bn' => $book->title_bn,
                    'language_primary' => $book->language_primary,
                    'isbn' => $book->isbn,
                    'description' => $book->language_primary === 'bangla' && $book->description_bn ? $book->description_bn : ($book->description_en ?: $book->description_bn),
                    'publication_year' => $book->publication_year,
                    'status' => $book->available_copies > 0 ? 'Available' : 'Unavailable',
                    'cover_image' => $book->cover_path ? Storage::url($book->cover_path) : null,
                    'primary_author' => $book->primaryAuthor ? [
                        'id' => $book->primaryAuthor->id,
                        'name' => $book->language_primary === 'bangla' && $book->primaryAuthor->name_bn ? $book->primaryAuthor->name_bn : ($book->primaryAuthor->name_en ?: $book->primaryAuthor->name_bn),
                        'name_bn' => $book->primaryAuthor->name_bn,
                    ] : null,
                    'publisher' => $book->publisher ? [
                        'id' => $book->publisher->id,
                        'name' => $book->language_primary === 'bangla' && $book->publisher->name_bn ? $book->publisher->name_bn : ($book->publisher->name_en ?: $book->publisher->name_bn),
                        'name_bn' => $book->publisher->name_bn,
                    ] : null,
                    'language' => $book->language ? [
                        'id' => $book->language->id,
                        'name' => $book->language->name,
                        'code' => $book->language->code,
                    ] : null,
                    'category' => $book->category ? [
                        'id' => $book->category->id,
                        'name' => $book->language_primary === 'bangla' && $book->category->name_bn ? $book->category->name_bn : ($book->category->name_en ?: $book->category->name_bn),
                        'name_bn' => $book->category->name_bn,
                    ] : null,
                    'tags' => $book->tags->map(function ($tag) {
                        return [
                            'id' => $tag->id,
                            'name' => $tag->name,
                        ];
                    }),
                ];
            });

        return response()->json(['books' => $books]);
    }
}
