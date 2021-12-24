<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreTaskRequest as StoreRequest;
use App\Http\Requests\Task\UpdateTaskRequest as UpdateRequest;
use App\Http\Requests\Task\FilterTaskRequest as ListRequest;
use App\Http\Resources\TaskResource as Resource;
use BusinessRules\Task\Task as TaskRules;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskController extends Controller
{
    public function __construct(private TaskRules $rules)
    {
    }

    /**
     * List Task
     *
     * Return all register
     * @group Task
     * @responseFile ApiResponse/TaskController/List.json
     * @responseFile ApiResponse/TaskController/ListGroup.json
     */
    public function list(ListRequest $request): JsonResource | JsonResponse
    {
        return $this->rules->list($request);
    }

    /**
     * Create new Task
     *
     * Create new Task
     * @group Task
     * @responseFile 201 ApiResponse/TaskController/Show.json
     * @responseFile 422 ApiResponse/TaskController/CreateValidation.json
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $novo = Task::create($request->validated());
        return (new Resource($novo))->response()->setStatusCode(201);
    }

    /**
     * Show Task
     *
     * Return Task Data
     * @group Task
     * @urlParam id integer required The register id.
     * @responseFile ApiResponse/TaskController/Show.json
     * @response 404 {"message": "No query results for model [App\\Models\\Task]"}
     * @response 403 {"message": "You dont have permission on this Task!"}
     */
    public function show(Task $task): JsonResource
    {
        abort_if(
            !in_array($task->project->getKey(), auth('sanctum')->user()->projects()->allRelatedIds()->toArray()),
            409,
            __('You are not on this project.')
        );
        return new Resource($task);
    }

    /**
     * Update Task
     *
     * Update Task Data
     * @group Task
     * @urlParam id integer required The register id.
     * @responseFile ApiResponse/TaskController/Show.json
     * @responseFile 422 ApiResponse/TaskController/UpdateValidation.json
     * @response 404 {"message": "No query results for model [App\\Models\\Task]"}
     * @response 403 {"message": "You are not on this project."}
     */
    public function update(UpdateRequest $request, Task $task): JsonResource
    {
        abort_if(
            !in_array($task->project->getKey(), auth('sanctum')->user()->projects()->allRelatedIds()->toArray()),
            409,
            __('You are not on this project.')
        );
        $task->update($request->validated());
        return new Resource($task);
    }

    /**
     * Delete Task
     *
     * Delete register do Task
     * @group Task
     * @urlParam id integer required The register id.
     * @response 200 {"message": "OK"}
     * @response 404 {"message": "No query results for model [App\\Models\\Task]"}
     * @response 403 {"message": "You are not on this project."}
     */
    public function destroy(Task $task): JsonResponse
    {
        abort_if(
            !in_array($task->project->getKey(), auth('sanctum')->user()->projects()->allRelatedIds()->toArray()),
            409,
            __('You are not on this project.')
        );
        $task->delete();
        return response()->json(['message' => 'OK']);
    }
}
