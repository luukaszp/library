<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavouriteAuthors extends Model
{
    /**
     * @var string
     */
    protected $table = 'favourite_authors';

    public function authors()
    {
        return $this->hasMany(Author::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
