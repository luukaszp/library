<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'ratings';

    protected $guarded = [];

    protected $fillable = [
        'book_id', 'user_id', 'rate'
    ];

    public function books()
    {
        return $this->belongsTo(Book::class);
    }
}