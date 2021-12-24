<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'     => ['required', 'string', 'max:25'],
            'email'    => ['required', 'string', 'unique:users,email', 'email'],
            'password' => ['required', 'string', 'confirmed', 'min:8', 'max:16'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'User name.',
                'example'     => 'Pedro Paulo'
            ],
            'email' => [
                'description' => 'User email.',
                'example'     => 'email@email.com'
            ],
            'password' => [
                'description' => 'User password.',
                'example'     => 'password@@@'
            ],
        ];
    }
}
