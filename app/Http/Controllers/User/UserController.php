<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserLoginRequest as LoginRequest;
use App\Http\Requests\User\UserRegisterRequest as RegisterRequest;
use BusinessRules\User\UserAuth as RuleUserAuth;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{

    public function __construct(private RuleUserAuth $regrasUserAuth)
    {
    }

    /**
     * Register new user
     *
     * Register new user
     * @group User
     * @responseFile 422 ApiResponse/UserController/RegisterValidation.json
     * @response 201 {"message": "User Registred."}
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        return $this->regrasUserAuth->register($request);
    }

    /**
     * User login
     *
     * User login
     * @group User
     * @responseFile 422 ApiResponse/UserController/LoginValidation.json
     * @response 200 {"token": "13|HfI40OFYLjWEahpM4QgWEvdqbXbVRpPIelNehKq0"}
     */
    public function login(LoginRequest $request): JsonResponse
    {
        return $this->regrasUserAuth->login($request);
    }

    /**
     * User logout
     *
     * User logout
     * @group User
     * @response 200 {"token": "User Logout."}
     */
    public function logout(): JsonResponse
    {
        return $this->regrasUserAuth->logout();
    }
}
