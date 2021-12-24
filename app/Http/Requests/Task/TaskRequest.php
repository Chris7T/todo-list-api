<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function bodyParameters(): array
    {
        return [
            'title' => [
                'description' => 'Task Title.',
                'example'     => 'First Task'
            ],
            'description' => [
                'description' => 'Task description.',
                'example'     => 'Create Project'
            ],
            'status' => [
                'description' => 'ProjTaskect status.',
                'example'     => 'ABERTO'
            ],
            'deadline' => [
                'description' => 'Task deadline.',
                'example'     => '25/12/2021'
            ],
            'project_id' => [
                'description' => 'Task project.',
                'example'     => 1
            ],
        ];
    }
}
