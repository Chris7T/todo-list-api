<?php

namespace App\Http\Requests;

use App\Rules\UniqueForUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProjectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function passedValidation()
    {
        $this->merge([
            'user_id' => Auth::user()->getKey(),
        ]);
    }

    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:60']
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'title' => [
                'description' => 'Project Title.',
                'example'     => 'New Project'
            ],
        ];
    }
}
