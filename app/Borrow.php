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
        'borrows_date', 'returns_date', 'delay', 'penalty'
    ];

    protected $casts = [
        'book_id' => 'array'
    ];

    public function readers()
    {
        return $this->belongsTo(Reader::class, 'reader_id');
    }

    public function books()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function workers()
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }
}
