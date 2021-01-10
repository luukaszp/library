<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;

class Reader extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'card_number', 'password'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'user_id' => $this->user_id,
            'card_number' => $this->card_number
        ];
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    public function opinions()
    {
        return $this->hasMany(Opinion::class);
    }

    public function suggestions()
    {
        return $this->hasMany(Suggestion::class);
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'author_reader', 'reader_id', 'author_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
