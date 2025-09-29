<?php

namespace Database\Factories;
use App\Models\TodoList;
use Illuminate\Database\Eloquent\Factories\Factory;

class TodoFactory extends Factory
{
    public function definition(): array
    {
        $todo_list = TodoList::factory()->create();

        return [
            'title' => $this->faker->name(),
            'description' => $this->faker->text(),
            'due_date' => $this->faker->date(),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high', 'urgent']),
            'todo_list_id' => $todo_list->getKey(),
            'user_id' => $todo_list->getAttribute('user_id'),
        ];
    }

}
