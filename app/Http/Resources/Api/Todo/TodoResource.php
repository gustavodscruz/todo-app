<?php

namespace App\Http\Resources\Api\Todo;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TodoResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'is_completed' => (bool)$this->is_completed,
            'due_date' => $this->due_date,
            'priority' => $this->priority,
            'completed_at' => $this->completed_at,
            'created_at' => $this->when($this->created_at, $this->created_at->toDateTimeString()),
            'updated_at' => $this->when($this->updated_at, $this->updated_at->toDateTimeString()),
        ];
    }
}
