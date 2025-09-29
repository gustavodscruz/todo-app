<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\V1\TodoStoreRequest;
use App\Http\Requests\Api\V1\TodoUpdateRequest;
use App\Http\Resources\Api\Todo\TodoResource;
use App\Services\Contracts\TodoServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;

class TodoController extends BaseApiController
{
    public function __construct(protected readonly TodoServiceInterface $todoService)
    {}

    public function index($todoListId): JsonResponse
    {
        $todos = $this->todoService->getTodos($todoListId);
        return $this->successResponse(TodoResource::collection($todos));
    }

    public function showPendingTasks(): JsonResponse
    {
        $todos = $this->todoService->getTodoByCompleted(false);
        return $this->successResponse(TodoResource::collection($todos));
    }

    public function show($todoId) : JsonResponse
    {
        $todo = $this->todoService->getTodoById($todoId);
        return $this->successResponse(new TodoResource($todo));
    }
    public function store(TodoStoreRequest $request) : JsonResponse  {
        logger()->debug('TodoController@store', $request->validated());

        $data = array_merge($request->validated(), ['user_id' => auth()->id()]);
        $todo = $this->todoService->createTodo($data);
        return $this->createdResponse(new TodoResource($todo));
    }

    public function update($id, TodoUpdateRequest $request) : JsonResponse {
        logger()->debug('TodoController@update', $request->validated());
        $todo = $this->todoService->updateTodo($id, $request->validated());
        return $this->successResponse(new TodoResource($todo));
    }

    public function destroy(int $id) : JsonResponse {
        $this->todoService->deleteTodo($id);
        return $this->noContentResponse();
    }


}
