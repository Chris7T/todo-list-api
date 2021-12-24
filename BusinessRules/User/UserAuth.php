<?php

namespace BusinessRules\User;

use App\Http\Requests\User\UserLoginRequest as LoginRequest;
use App\Http\Requests\User\UserRegisterRequest as RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class UserAuth
{
    public function register(RegisterRequest $request): JsonResponse
    {
        User::create($request->validated());

        return response()->json(['message' => 'User Registred.'], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();
        if ($user == null || $this->failCheckPassword($user, $request->input('password'))) {
            return response()->json(['message' => 'Login error.'], 401);
        }
        $token = $user->createToken('Hospital-Test');

        return response()->json(['token' => $token->plainTextToken], 200);
    }

    public function logout(): JsonResponse
    {
        Auth::user()->currentAccessToken()->delete();
        return response()->json(['message' => 'User Logout.'], 200);
    }

    private function failCheckPassword(User $user, string $password): bool
    {
        return Crypt::decryptString($user->password) != $password
            ? true
            : false;
    }
}
