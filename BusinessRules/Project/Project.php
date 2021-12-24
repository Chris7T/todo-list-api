<?php

namespace BusinessRules\Project;

use App\Http\Requests\ProjectRequest as Request;
use App\Models\Project as Model;
use Illuminate\Support\Facades\DB;

class Project
{
    public function register(Request $request): object
    {
        return DB::transaction(function () use ($request) {
            $project = Model::create($request->validated());
            $project->users()->attach($request->input('user_id'), ['owner' => true]);
            return $project;
        });
    }
}
