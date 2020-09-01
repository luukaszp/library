<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * @var string
     */
    protected $table = 'events';

    /**
     * @var array
     */

    protected $fillable = [
        'name', 'date', 'time',
    ];

    public function types()
    {
        return $this->belongsTo(Type::class);
    }
}
