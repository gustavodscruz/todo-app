<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Todo extends Authenticatable implements JWTSubject
{
    protected $fillable = [];

    protected $hidden = [];

    protected function casts(): array
    {
        return [];
    }

    public function getJWTIdentifier()
    {
        // TODO: Implement getJWTIdentifier() method.
    }


    public function getJWTCustomClaims()
    {
        // TODO: Implement getJWTCustomClaims() method.
    }

}
