<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class TodoList extends Authenticatable implements JWTSubject
{

    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'priority',
        'is_completed',
        'due_date',
        'is_archived'
    ];

    protected $hidden = [
        'user_id'
    ];

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    public function scopePriority($query, Priority $priority)
    {
        return $query->where('priority', $priority->value);
    }


    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
            'is_archived' => 'boolean',
            'due_date' => 'datetime',
            'priority' => Priority::class,
        ];
    }

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

}
