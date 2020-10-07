<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opinion extends Model
{
    /**
     * @var string
     */
    protected $table = 'opinions';

    /**
     * @var array
     */
    protected $fillable = [
        'book_id', 'user_id', 'opinion'
    ];

    public function books()
    {
        return $this->belongsTo(Book::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
