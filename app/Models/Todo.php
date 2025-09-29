<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Todo extends Authenticatable implements JWTSubject
{
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
            'is_archived' => 'boolean',
            'due_date' => 'datetime',
            'completed_at' => 'datetime',
            'priority' => Priority::class,
        ];
    }

}
