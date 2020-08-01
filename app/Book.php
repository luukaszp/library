<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /**
     * @var string
     */
    protected $table = 'books';
    protected $hidden = ['pivot'];

    /**
     * @var array
     */
    protected $guarded = [];

    protected $fillable = [
        'isbn', 'title', 'description', 'publish_year',
    ];
}
