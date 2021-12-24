<?php

namespace BusinessRules\Task;

use App\Http\Requests\Task\FilterTaskRequest as Request;
use App\Http\Resources\TaskResource;
use App\Models\Task as Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class Task
{
    public function list(Request $request): JsonResource | JsonResponse
    {
        $projectsIds = Auth::user()->projects()->with('tasks')->pluck('projects.id')->toArray();
        $tasks = Model::whereIn('project_id', $projectsIds);;

        if ($request->input('status')) {
            $tasks->where('status', $request->input('status'));
        }
        if ($request->input('deadline')) {
            $tasks->where('deadline', $request->input('deadline'));
        }
        if ($request->input('project_id')) {
            $tasks->where('project_id', $request->input('project_id'));
        }
        if ($request->input('group')) {
            if ($request->input('group') == 'deadline') {
                return response()->json(['data' => TaskResource::collection($tasks->orderBy('deadline', 'asc')->get())->groupBy($request->input('group'))]);
            }
            return response()->json(['data' => TaskResource::collection($tasks->get())->groupBy($request->input('group'))]);
        }

        return TaskResource::collection($tasks->orderBy('updated_at', 'desc')->get());
    }
}
