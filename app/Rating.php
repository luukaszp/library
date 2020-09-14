<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    /**
     * @var string
     */
    protected $table = 'ratings';

    /**
     * @var array
     */
    protected $fillable = [
        'book_id', 'user_id', 'rate'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'rate' => 'integer'
    ];

    public function books()
    {
        return $this->belongsTo(Book::class);
    }
}