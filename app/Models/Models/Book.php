<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Book extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'title_en','title_bn','title_bn_translit','author_en','author_bn','category_id','publisher_en','publisher_bn','isbn','barcode','publication_year','pages','language_primary','description_en','description_bn','cover_path','pdf_path','available_copies','total_copies'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'book_tag');
    }

    public function toSearchableArray()
    {
        return [
            'title_en' => $this->title_en,
            'title_bn' => $this->title_bn,
            'title_bn_translit' => $this->title_bn_translit,
            'author_en' => $this->author_en,
            'author_bn' => $this->author_bn,
            'publisher_en' => $this->publisher_en,
            'publisher_bn' => $this->publisher_bn,
            'isbn' => $this->isbn,
            'tags' => $this->tags()->pluck('name')->toArray(),
        ];
    }

    public function searchableAs()
    {
        return 'books';
    }
}
