<?php

namespace App\Repositories\TodoList\Concretes;

use App\Models\TodoList;
use App\Repositories\Base\Concretes\QueryableRepository;
use App\Repositories\TodoList\Contracts\TodoListRepositoryInterface;


class TodoListRepository extends QueryableRepository implements TodoListRepositoryInterface
{
    protected function model(): string
    {
        return TodoList::class;
    }

}
