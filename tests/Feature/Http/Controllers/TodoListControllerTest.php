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



it('can show a single todo_list', function () {
    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);

    $todoList = TodoList::factory()->create(['user_id' => $user->id]);

    $this
        ->withToken($token)
        ->getJson(route('api.v1.todo-lists.show', $todoList->id))
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'due_date',
                'priority',
                'todos',
                'is_completed',
                'is_archived',
                'completed_at',
                'created_at',
                'updated_at'
            ]
        ])
        ->assertJson([
            'data' => [
                'id' => $todoList->id,
                'title' => $todoList->title,
                'is_completed' => false,
                'is_archived' => false,
            ]
        ]);
});

it('can list all todo_lists for authenticated user', function () {
    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);

    TodoList::factory(3)->create(['user_id' => $user->id]);

    $otherUser = User::factory()->create();
    TodoList::factory(2)->create(['user_id' => $otherUser->id]);

    $this
        ->withToken($token)
        ->getJson(route('api.v1.todo-lists.index'))
        ->assertOk()
        ->assertJsonCount(3, 'data') // deve retornar apenas os 3 do usuário autenticado
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'priority',
                    'is_completed',
                    'is_archived',
                ]
            ]
        ]);
});

it('can update a todo_list', function () {
    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);

    $todoList = TodoList::factory()->create(['user_id' => $user->id]);

    $updateData = [
        'title' => 'Updated TodoList Title',
        'description' => 'Updated description',
        'priority' => 'high',
        'is_completed' => true,
    ];

    $this
        ->withToken($token)
        ->putJson(route('api.v1.todo-lists.update', $todoList->id), $updateData)
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'priority',
                'is_completed',
                'completed_at',
            ]
        ])
        ->assertJson([
            'data' => [
                'id' => $todoList->id,
                'title' => 'Updated TodoList Title',
                'description' => 'Updated description',
                'priority' => 'high',
                'is_completed' => true,
            ]
        ]);

    expect($this->getJson(route('api.v1.todo-lists.show', $todoList->id))->json('data.completed_at'))
        ->not()->toBeNull();
});

it('sets completed_at to null when marking todo_list as incomplete', function () {
    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);

    $todoList = TodoList::factory()->create([
        'user_id' => $user->id,
        'is_completed' => true,
        'completed_at' => now(),
    ]);

    $this
        ->withToken($token)
        ->putJson(route('api.v1.todo-lists.update', $todoList->id), [
            'is_completed' => false,
        ])
        ->assertOk()
        ->assertJson([
            'data' => [
                'is_completed' => false,
                'completed_at' => null,
            ]
        ]);
});

it('returns 404 when trying to show non-existent todo_list', function () {
    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);

    $this
        ->withToken($token)
        ->getJson(route('api.v1.todo-lists.show', 99999))
        ->assertNotFound();
});

it('returns 404 when trying to update non-existent todo_list', function () {
    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);

    $this
        ->withToken($token)
        ->putJson(route('api.v1.todo-lists.update', 99999), [
            'title' => 'Updated Title',
        ])
        ->assertNotFound();
});
