<?php

use App\Models\Todo;
use App\Models\TodoList;
use \App\Models\User;

it('can create todo', function () {
    $this->withoutExceptionHandling();

    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);

    $task_list = TodoList::factory()->create();

    $this
        ->withToken($token)
        ->postJson(route('api.v1.todos.store'),
            [
                'title' => 'Test TodoList',
                'description' => 'Test TodoList',
                'priority' => 'low',
                'due_date' => now()->addDays(30),
                'todo_list_id' => $task_list->getKey(),
            ])
        ->assertCreated();

});

it('can delete todo', function () {
    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);

    $task = Todo::factory()->create();

    $this
        ->withToken($token)
        ->deleteJson(route('api.v1.todos.destroy', $task->getKey()))
        ->assertNoContent();
});

it('can read todos from todo list', function () {
    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);

    $todo = Todo::factory()->create();

    $response = $this
        ->withToken($token)
        ->getJson(route('api.v1.todos.show', [$todo->getAttribute('todo_list_id')]))
        ->assertOk();

    $response_data = $response->json();
    logger()->debug('Response Json: ', $response_data);
});

it('can update todo', function () {
    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);

    $todo = Todo::factory()->create();

    $result = $this
        ->withToken($token)
        ->putJson(route('api.v1.todos.update', [$todo->getKey()]), [
            'title' => 'Test Update',
            'description' => 'Test Update',
            'priority' => 'high',
            'due_date' => now(),
            'is_completed' => true,
        ])
        ->assertJsonStructure(
            ['data' => [
                'title',
                'description',
                'priority',
                'due_date',
                'is_completed',
                'completed_at',
                'created_at',
                'updated_at',
            ]]
        )
        ->assertOk();

    $responseData = $result->json();
    logger()->debug('Response JSON do TodoController@update:', $responseData);
});

it('can read all pending tasks', function () {
    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);


    for ($x = 1; $x <= 5; $x++) {
        if ($x > 3) {
            Todo::factory()->create([
                'is_completed' => true,
                'user_id' => $user->getKey(),
            ]);
            continue;
        }

        Todo::factory()->create([
            'user_id' => $user->getKey(),
        ]);
    }

    $result = $this
        ->withToken($token)
        ->getJson(route('api.v1.todos.pending'))
        ->assertJsonCount(3, 'data')
        ->assertOk();

    $responseData = $result->json();
    logger()->debug('Response JSON do PendingTasks', $responseData);
});
