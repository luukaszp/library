<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /**
     * @var string
     */
    protected $table = 'books';

    /**
     * @var array
     */

    protected $fillable = [
        'isbn', 'title', 'description', 'publish_year', 'cover',
    ];

    public function authors()
    {
        return $this->belongsTo(Author::class);
    }

    public function publishers()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function categories()
    {
        return $this->belongsTo(Category::class);
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    public function opinions()
    {
        return $this->hasMany(Opinion::class);
    }

    public function favouriteBooks()
    {
        return $this->belongsTo(favouriteBooks::class);
    }
}
