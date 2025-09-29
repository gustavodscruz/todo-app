<?php

namespace App\Services\Concretes;

use App\Models\Priority;
use App\Models\TodoList;
use App\Repositories\Todo\Contracts\TodoRepositoryInterface;
use App\Services\Base\Concretes\BaseService;
use App\Services\Contracts\TodoServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TodoService extends BaseService implements TodoServiceInterface
{

    public function __construct(protected TodoRepositoryInterface $todoRepository)
    {
        $this->setRepository($todoRepository);
    }

    public function getTodoById(int $id): Model
    {
        try {
            return $this->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Todo not found');
        }
    }

    public function getTodos(int $id): Collection
    {
        return $this->repository->query()->where('todo_list_id', $id)->get();
    }

    public function createTodo($data): Model
    {

        return $this->repository->create($data);
    }

    public function updateTodo($id, $data): Model
    {
        return $this->repository->update($id, $data);
    }

    public function deleteTodo(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function getTodoByPriority(Priority $priority): Collection
    {
        return $this->repository
            ->query()
            ->where('user_id', auth()->id())
            ->where('priority', $priority->value)
            ->get();
    }

    public function getTodoByArchived(bool $isArchived): Collection
    {
        return $this->repository
            ->query()
            ->where('user_id', auth()->id())
            ->where('is_archived', $isArchived)
            ->get();
    }

    public function getTodoByCompleted(bool $isCompleted): Collection
    {
        return $this->repository
            ->query()
            ->where('user_id', auth()->id())
            ->where('is_completed', $isCompleted)
            ->get();
    }

}
