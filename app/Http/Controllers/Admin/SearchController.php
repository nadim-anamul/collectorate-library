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

        $useScout = config('scout.driver') && config('scout.driver') !== 'null' && class_exists(\Laravel\Scout\Builder::class);

        if($useScout){
            $results = Book::search($q)->take(10)->get();
        }else{
            $results = Book::query()
                ->where(function($w) use ($q){
                    $w->where('title_en','like','%'.$q.'%')
                      ->orWhere('title_bn','like','%'.$q.'%')
                      ->orWhere('title_bn_translit','like','%'.$q.'%')
                      ->orWhere('author_en','like','%'.$q.'%')
                      ->orWhere('author_bn','like','%'.$q.'%');
                })
                ->limit(10)
                ->get();
        }

        return response()->json($results->map(function($b){
            return [
                'id' => $b->id,
                'title_en' => $b->title_en,
                'title_bn' => $b->title_bn,
                'author_en' => $b->author_en,
                'author_bn' => $b->author_bn,
                'isbn' => $b->isbn,
            ];
        }));
    }
}
