<?php

namespace App\Http\Requests\Task;

use App\Rules\groupTask;
use App\Rules\status;
use App\Rules\statusTask;
use Illuminate\Support\Facades\Auth;

class FilterTaskRequest extends TaskRequest
{
    public function passedValidation()
    {
        $this->merge([
            'user_id' => Auth::user()->getKey(),
        ]);
    }

    public function rules()
    {
        return [
            'deadline'   => ['filled', 'date_format:Y-m-d'],
            'project_id' => ['filled', 'integer', 'exists:projects,id'],
            'status'     => ['filled', 'string', new statusTask],
            'group'      => ['filled', 'string', new groupTask]
        ];
    }

    public function bodyParameters(): array
    {
        return [
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
            'group' => [
                'description' => 'Group tasks.',
                'example'     => 'deadline'
            ],
        ];
    }
}
