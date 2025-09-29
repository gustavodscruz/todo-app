<?php

namespace App\Services\Contracts;

use App\Models\Priority;
use App\Services\Base\Contracts\BaseServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface TodoServiceInterface extends BaseServiceInterface
{
    public function getTodoById(int $id) : Model;

    public function getTodos(int $id) : Collection;

    public function createTodo($data) : Model;

    public function updateTodo($id, $data) : Model;

    public function deleteTodo(int $id) : bool;

    public function getTodoByPriority(Priority $priority) : Collection;

    public function getTodoByArchived(bool $isArchived) : Collection;

    public function getTodoByCompleted(bool $isCompleted) : Collection;


}
