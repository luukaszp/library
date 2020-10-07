<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavouriteBooks extends Model
{
    /**
     * @var string
     */
    protected $table = 'favourite_books';

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
