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
        'type', 'description', 'user_id'
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
