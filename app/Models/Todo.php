<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Todo extends Authenticatable implements JWTSubject
{

    use HasFactory, Notifiable;
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
