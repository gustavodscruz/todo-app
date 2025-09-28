<?php

namespace App\Http\Resources\Api\TodoList;

use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin TodoList
 */
class TodoListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->when($this->created_at, $this->created_at->toDateTimeString()),
            'updated_at' => $this->when($this->updated_at, $this->updated_at->toDateTimeString())
        ];
    }
}
