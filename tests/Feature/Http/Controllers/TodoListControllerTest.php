<?php


use \App\Models\User;

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
