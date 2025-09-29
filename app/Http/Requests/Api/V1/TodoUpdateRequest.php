<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class TodoUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'priority' => ['sometimes', 'string', 'in:low,medium,high,urgent'],
            'due_date' => ['sometimes', 'date'],
            'title' => ['sometimes', 'string'],
            'is_completed' => ['sometimes', 'boolean'],
        ];
    }
}
