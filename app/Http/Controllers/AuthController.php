<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (auth()->attempt($credentials)) {

            $token = auth()->user()->createToken(
                'auth_token',
                ['*'],
                now()->addWeek()
            );

            return $this->response([
                'token' => $token->plainTextToken
            ], true);

        } else {

            return $this->response(
                [
                    'errors' => [
                        'email' => ['The provided credentials are incorrect.']
                    ],
                ],
                false,
                422
            );
        }
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::query()->create($validated);

        $token = $user->createToken(
            'auth_token',
            ['*'],
            now()->addWeek()
        );

        return $this->response([
            'token' => $token->plainTextToken
        ], true, 201);
    }

    public function logout(): JsonResponse
    {
        $user = auth()->user();

        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        return $this->response([
            'message' => 'Logged out successfully'
        ], true);
    }
}
