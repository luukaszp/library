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
        'isbn', 'title', 'description', 'publish_year', 'cover'
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'author_book', 'book_id', 'author_id');
    }

    public function publishers()
    {
        return $this->belongsTo(Publisher::class, 'publisher_id');
    }

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    public function opinions()
    {
        return $this->hasMany(Opinion::class);
    }
}
