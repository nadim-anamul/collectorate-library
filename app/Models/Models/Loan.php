<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
        'issued_by_user_id',
        'requested_at',
        'requested_due_at',
        'issued_at',
        'due_at',
        'returned_at',
        'status',
        'decline_reason',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Legacy member relation intentionally removed in user-centric flow

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
