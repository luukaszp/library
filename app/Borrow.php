<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    /**
     * @var string
     */
    protected $table = 'borrows';

    /**
     * @var array
     */

    protected $fillable = [
        'borrows_date', 'returns_date',
    ];

    protected $casts = [
        'book_id' => 'array'
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function books()
    {
        return $this->belongsTo(Book::class);
    }
}
