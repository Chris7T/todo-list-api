<?php

namespace App\Http\Requests\Task;

use App\Rules\statusTask;

class UpdateTaskRequest extends TaskRequest
{
    public function rules()
    {
        return [
            'title'       => ['filled', 'string', 'max:60'],
            'description' => ['filled', 'string', 'max:200'],
            'status'      => ['filled', 'string', new statusTask],
            'deadline'    => ['filled', 'date_format:Y-m-d']
        ];
    }
}
