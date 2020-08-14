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
        'name', 'surname',
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
