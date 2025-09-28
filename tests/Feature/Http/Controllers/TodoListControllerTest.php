<?php


use App\Models\TodoList;
use \App\Models\User;
use Illuminate\Testing\TestResponse;
use function Pest\Laravel\withToken;


it('can create todo_list', function () {

    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);

    $this
        ->withToken($token)
        ->postJson(route('api.v1.todo-lists.store'),
            [
                'title' => 'Test TodoList',
                'description' => 'Test TodoList',
                'priority' => 'low',
                'due_date' => now()->addDays(30),
            ])
        ->assertCreated();
});

it('can delete todo_list', function () {
    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);

    $todoList = TodoList::factory()->create(['user_id' => $user->id]);

    $this
        ->withToken($token)
        ->deleteJson(route('api.v1.todo-lists.destroy', $todoList->getKey()))
        ->assertNoContent();
});
