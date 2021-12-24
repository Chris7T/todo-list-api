<?php

namespace App\Http\Requests\Task;

use App\Rules\statusTask;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends TaskRequest
{
    public function rules()
    {
        return [
            'project_id'  => ['required', 'integer', 'exists:projects,id'],
            'title'       => ['required_with:project_id', 'string', 'max:60', Rule::unique('tasks')->where('project_id', $this->input('project_id'))],
            'description' => ['filled', 'string', 'max:200'],
            'status'      => ['filled', 'string', new statusTask],
            'deadline'    => ['filled', 'date_format:Y-m-d'],
        ];
    }
}
