<?php

namespace App\Services\Concretes;

use App\Models\TodoList;
use App\Repositories\TodoList\Concretes\TodoListRepository;
use App\Repositories\TodoList\Contracts\TodoListRepositoryInterface;
use App\Repositories\User\Contracts\UserRepositoryInterface;
use App\Services\Base\Concretes\BaseService;
use App\Services\Contracts\TodoListServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TodoListService extends BaseService implements TodoListServiceInterface
{

    public function __construct(protected TodoListRepositoryInterface $todoListRepository)
    {
        $this->setRepository($this->todoListRepository);
    }

    public function getTodoLists(int $id): Collection
    {
        return $this->repository->query()->where('user_id', $id)->get();
    }

    public function getTodoListById(int $id): Model
    {
        try {
            return $this->repository->query()->with('todos')->findOrFail($id);

        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Todo list not found');
        }
    }


    public function createTodoList($data): Model
    {
        return $this->repository->create($data);
    }

    public function completeTodoList(int $id): bool
    {
        try {
            $todoList = $this->repository->findOrFail($id);
            $todoList->is_completed = true;
            return $todoList->save();
        } catch (ModelNotFoundException) {
            throw new ModelNotFoundException('Todo list not found');
        }
    }

    public function updateTodoList($id, $data): Model
    {
        try {
            $payload = is_array($data) ? $data : (array)$data;

            if (array_key_exists('is_completed', $payload) && $payload['is_completed']) {
                $payload['completed_at'] = now()->toDateTimeString();
            }

            if (array_key_exists('is_completed', $payload) && !$payload['is_completed']) {
                $payload['completed_at'] = null;
            }

            return $this->repository->update($id, $payload);
        } catch (ModelNotFoundException) {
            throw new ModelNotFoundException('Todo List not found');
        }
    }

    public function deleteTodoList($id): bool
    {
        return $this->repository->delete($id);
    }

}
