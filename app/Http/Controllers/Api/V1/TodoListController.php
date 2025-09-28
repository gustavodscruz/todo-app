<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\V1\TodoListStoreRequest;
use App\Http\Requests\Api\V1\UserStoreRequest;
use App\Http\Requests\Api\V1\UserUpdateRequest;
use App\Http\Resources\Api\TodoList\TodoListResource;
use App\Http\Resources\Api\User\UserResource;
use App\Models\TodoList;
use App\Services\Contracts\TodoListServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class TodoListController extends BaseApiController
{
    public function __construct(protected readonly TodoListServiceInterface $todoListService)
    {}

    public function store(TodoListStoreRequest $request): JsonResponse
    {

        logger()->debug('TodoListController@store', $request->validated());

        $data = array_merge($request->validated(), ['user_id' => auth()->id()]);

        $todo = $this->todoListService->createTodoList($data);

        return $this->createdResponse(new TodoListResource($todo));
    }

    public function index() : JsonResponse
    {
        logger()->debug('TodoListController@findAllByUser');

        $id = auth()->id();

        $todo_lists = $this->todoListService->getTodoLists($id);

        return $this->successResponse(Collection::make($todo_lists));
    }

    public function show(int $id) : JsonResponse
    {
        logger()->debug('TodoListController@show', [$id]);

        $todo_list = $this->todoListService->getTodoListById($id);

        return $this->successResponse(new TodoListResource($todo_list));
    }

}
