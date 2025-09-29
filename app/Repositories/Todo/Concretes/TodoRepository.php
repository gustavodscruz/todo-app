<?php

namespace App\Repositories\Todo\Concretes;

use App\Models\Todo;
use App\Repositories\Base\Concretes\QueryableRepository;
use App\Repositories\Todo\Contracts\TodoRepositoryInterface;

class TodoRepository extends QueryableRepository implements TodoRepositoryInterface
{
    protected function model(): string
    {
        return Todo::class;
    }

}
