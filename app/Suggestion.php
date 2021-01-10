<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    /**
     * @var string
     */
    protected $table = 'suggestions';

    /**
     * @var array
     */
    protected $fillable = [
        'type', 'description', 'reader_id'
    ];

    public function readers()
    {
        return $this->belongsTo(Reader::class, 'reader_id');
    }
}
