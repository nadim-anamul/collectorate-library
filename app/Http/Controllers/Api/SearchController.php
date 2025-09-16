<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Models\Book;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchBooks(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['books' => []]);
        }

        // Start with a simple search and build up
        $books = Book::with(['primaryAuthor', 'publisher', 'category', 'language', 'tags'])
            ->where('title_en', 'like', "%{$query}%")
            ->orWhere('title_bn', 'like', "%{$query}%")
            ->orWhere('isbn', 'like', "%{$query}%")
            ->orderBy('title_en')
            ->limit(20)
            ->get()
            ->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title_en ?: $book->title_bn,
                    'title_bn' => $book->title_bn,
                    'isbn' => $book->isbn,
                    'description' => $book->description_en ?: $book->description_bn,
                    'publication_year' => $book->publication_year,
                    'status' => $book->available_copies > 0 ? 'Available' : 'Unavailable',
                    'cover_image' => $book->cover_path,
                    'primary_author' => $book->primaryAuthor ? [
                        'id' => $book->primaryAuthor->id,
                        'name' => $book->primaryAuthor->name_en ?: $book->primaryAuthor->name_bn,
                        'name_bn' => $book->primaryAuthor->name_bn,
                    ] : null,
                    'publisher' => $book->publisher ? [
                        'id' => $book->publisher->id,
                        'name' => $book->publisher->name_en ?: $book->publisher->name_bn,
                        'name_bn' => $book->publisher->name_bn,
                    ] : null,
                    'language' => $book->language ? [
                        'id' => $book->language->id,
                        'name' => $book->language->name,
                        'code' => $book->language->code,
                    ] : null,
                    'category' => $book->category ? [
                        'id' => $book->category->id,
                        'name' => $book->category->name_en ?: $book->category->name_bn,
                        'name_bn' => $book->category->name_bn,
                    ] : null,
                    'tags' => $book->tags->map(function ($tag) {
                        return [
                            'id' => $tag->id,
                            'name' => $tag->name_en ?: $tag->name_bn,
                            'name_bn' => $tag->name_bn,
                        ];
                    }),
                ];
            });

        return response()->json(['books' => $books]);
    }
}
