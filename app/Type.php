<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
   /**
     * @var string
     */
    protected $table = 'types';

    /**
     * @var array
     */
    protected $guarded = [];

    protected $fillable = [
        'name',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
