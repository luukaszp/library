<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    /**
     * @var string
     */
    protected $table = 'authors';

    /**
     * @var array
     */
    protected $guarded = [];

    protected $fillable = [
        'name', 'surname', 'description', 'photo'
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'author_book', 'author_id', 'book_id');
    }

    public function readers()
    {
        return $this->belongsToMany(Reader::class, 'author_reader', 'author_id', 'reader_id');
    }
}
