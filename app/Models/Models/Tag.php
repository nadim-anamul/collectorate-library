<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name','slug'];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_tag');
    }
}
