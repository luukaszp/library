<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'email', 'card_number', 'id_number', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'is_worker' => 'boolean',
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
            'email' => $this->email,
            'card_number' => $this->card_number,
            'id_number' => $this->id_number,
            'is_admin' => $this->is_admin,
            'is_worker' => $this->is_worker,
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
        return $this->belongsToMany(Author::class, 'author_user', 'user_id', 'author_id');
    }
}
