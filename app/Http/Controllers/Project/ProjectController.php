<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest as Request;
use App\Http\Resources\ProjectResource as Resource;
use App\Models\Project;
use App\Models\User;
use BusinessRules\Project\Project as ProjectRules;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class ProjectController extends Controller
{
    private const MESSAGE = 'You dont have permission on this Project!';

    public function __construct(private ProjectRules $rules)
    {
    }

    /**
     * List Project
     *
     * Return all register
     * @group Project
     * @responseFile ApiResponse/ProjectController/List.json
     */
    public function index()
    {
        return Resource::collection(User::find(auth('sanctum')->user()->getAuthIdentifier())->projects()->get());
    }

    /**
     * Create new Project
     *
     * Create new Project
     * @group Project
     * @responseFile 201 ApiResponse/ProjectController/Show.json
     * @responseFile 422 ApiResponse/ProjectController/Validation.json
     */
    public function store(Request $request): JsonResponse
    {
        $novo = $this->rules->register(($request));
        return (new Resource($novo))->response()->setStatusCode(201);
    }

    /**
     * Show Project
     *
     * Return Project Data
     * @group Project
     * @urlParam id integer required The register id.
     * @responseFile ApiResponse/ProjectController/Show.json
     * @response 404 {"message": "No query results for model [App\\Models\\Project]"}
     * @response 403 {"message": "You dont have permission on this Project!"}
     */
    public function show(Project $project): JsonResource
    {
        abort_if(
            !in_array(auth('sanctum')->user()->getAuthIdentifier(), $project->users()->allRelatedIds()->toArray()),
            403,
            __(self::MESSAGE)
        );
        return new Resource($project);
    }

    /**
     * Update Project
     *
     * Update Project Data
     * @group Project
     * @urlParam id integer required The register id.
     * @responseFile ApiResponse/ProjectController/Show.json
     * @responseFile 422 ApiResponse/ProjectController/Validation.json
     * @response 404 {"message": "No query results for model [App\\Models\\Project]"}
     * @response 403 {"message": "You dont have permission on this Project!"}
     */
    public function update(Request $request, Project $project): JsonResource
    {
        abort_if(
            !in_array(auth('sanctum')->user()->getAuthIdentifier(), $project->users()->allRelatedIds()->toArray()),
            403,
            __(self::MESSAGE)
        );
        $project->update($request->validated());
        return new Resource($project);
    }

    /**
     * Delete Project
     *
     * Delete register do Project
     * @group Project
     * @urlParam id integer required The register id.
     * @response 200 {"message": "OK"}
     * @response 404 {"message": "No query results for model [App\\Models\\Project]"}
     * @response 403 {"message": "You dont have permission on this Project!"}
     */
    public function destroy(Project $project): JsonResponse
    {
        abort_if(
            !$project->users()->findOrFail(auth('sanctum')->user()->getAuthIdentifier(), ['users_projects'])->pivot->owner,
            403,
            __(self::MESSAGE)
        );
        $project->delete();
        return response()->json(['message' => 'OK']);
    }

    /**
     * Link Project
     *
     * Link User to Project
     * @group Project
     * @urlParam key integer required The key of project.
     * @response 200 {"message": "Project linked."}
     * @response 404 {"message": "No query results for model [App\\Models\\Project]"}
     */
    public function link(String $key): JsonResponse
    {
        $project = Project::findOrFail(Crypt::decryptString($key));
        abort_if(
            in_array(auth('sanctum')->user()->getAuthIdentifier(), $project->users()->allRelatedIds()->toArray()),
            409,
            __('You are already in this project')
        );
        $project->users()->attach(auth('sanctum')->user()->getAuthIdentifier());
        return response()->json(['message' => 'Project linked.']);
    }

    /**
     * Unlink Project
     *
     * Unlink User to Project
     * @group Project
     * @urlParam id integer required The register id.
     * @response 200 {"message": "Project removed."}
     * @response 404 {"message": "No query results for model [App\\Models\\Project]"}
     */
    public function unlink(Project $project): JsonResponse
    {
        abort_if(
            !in_array(auth('sanctum')->user()->getAuthIdentifier(), $project->users()->allRelatedIds()->toArray()),
            409,
            __('You are not on this project.')
        );
        abort_if(
            $project->users()->findOrFail(auth('sanctum')->user()->getAuthIdentifier(), ['users_projects'])->pivot->owner,
            409,
            __('You can not unlink this project because you are the owner.')
        );
        $project->users()->detach(auth('sanctum')->user()->getAuthIdentifier());
        return response()->json(['message' => 'Project removed.']);
    }

    /**
     * Generate link Project
     *
     * Generate link Project to other users
     * @group Project
     * @urlParam id integer required The register id.
     * @response 200 {"message": "/project/link/{{key}}"}
     * @response 404 {"message": "No query results for model [App\\Models\\Project]"}
     * @response 403 {"message": "You dont have permission on this Project!"}
     */
    public function generateLink(Project $project): JsonResponse
    {
        abort_if(
            !$project->users()->findOrFail(auth('sanctum')->user()->getAuthIdentifier(), ['users_projects'])->pivot->owner,
            403,
            __(self::MESSAGE)
        );
        return response()->json(['message' => Crypt::encryptString($project->getKey())]);
    }
}
