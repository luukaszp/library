<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    /**
     * @var string
     */
    protected $table = 'publishers';
    protected $hidden = ['pivot'];

    /**
     * @var array
     */
    protected $guarded = [];

    protected $fillable = [
        'name',
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
