<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Language;
use App\Models\Models\Category;
use App\Models\Models\Tag;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_en','title_bn','title_bn_translit','author_en','author_bn','category_id','primary_author_id','publisher_en','publisher_bn','publisher_id','isbn','barcode','publication_year','pages','language_primary','language_id','description_en','description_bn','cover_path','pdf_path','available_copies','total_copies'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'author_book');
    }

    public function primaryAuthor()
    {
        return $this->belongsTo(Author::class, 'primary_author_id');
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'book_tag');
    }

}
