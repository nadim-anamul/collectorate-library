<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Models\Book;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function suggestBooks(Request $request)
    {
        $q = trim((string)$request->get('q',''));
        if($q === ''){
            return response()->json([]);
        }

        $useScout = config('scout.driver') && config('scout.driver') !== 'null' && class_exists(\Laravel\Scout\Builder::class) && method_exists(Book::class, 'search');

        if($useScout){
            try {
                $results = Book::search($q)->take(10)->get();
            } catch (\Exception $e) {
                // Fall back to database search if Scout fails
                $useScout = false;
            }
        }
        
        if(!$useScout){
            $results = Book::with(['primaryAuthor', 'publisher'])
                ->where(function($w) use ($q){
                    $w->where('title_en','like','%'.$q.'%')
                      ->orWhere('title_bn','like','%'.$q.'%')
                      ->orWhere('isbn','like','%'.$q.'%')
                      ->orWhereHas('primaryAuthor', function ($subQ) use ($q) {
                          $subQ->where('name_en', 'like', '%'.$q.'%')
                               ->orWhere('name_bn', 'like', '%'.$q.'%');
                      })
                      ->orWhereHas('publisher', function ($subQ) use ($q) {
                          $subQ->where('name_en', 'like', '%'.$q.'%')
                               ->orWhere('name_bn', 'like', '%'.$q.'%');
                      });
                })
                ->limit(10)
                ->get();
        }

        return response()->json($results->map(function($b){
            return [
                'id' => $b->id,
                'title_en' => $b->title_en,
                'title_bn' => $b->title_bn,
                'author_en' => $b->primaryAuthor ? $b->primaryAuthor->name_en : null,
                'author_bn' => $b->primaryAuthor ? $b->primaryAuthor->name_bn : null,
                'publisher_en' => $b->publisher ? $b->publisher->name_en : null,
                'publisher_bn' => $b->publisher ? $b->publisher->name_bn : null,
                'isbn' => $b->isbn,
            ];
        }));
    }
}
