<?php

namespace App\Services\Contracts;

use App\Models\TodoList;
use App\Services\Base\Contracts\BaseServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface TodoListServiceInterface extends BaseServiceInterface
{
    public function getTodoLists(int $id) : Collection;

    public function getTodoListById(int $id) : TodoList;

    public function createTodoList($data) : Model;

    public function completeTodoList(int $id) : bool;

    public function updateTodoList($id, $data) : Model;

    public function deleteTodoList($id) : bool;

}
