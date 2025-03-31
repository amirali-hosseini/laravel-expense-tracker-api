<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Models\User;
use Hash;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public static $token_name = 'auth-token';

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {

            return $this->jsonResponse([
                'errors' => ['email' => ['The provided credentials are incorrect.']]
            ], false, 422);

        }

        $token = $user->createToken(self::$token_name, ['*'], now()->addMonth());

        return $this->jsonResponse([
            'token' => $token->plainTextToken
        ]);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::query()->create($validated);

        $token = $user->createToken(
            self::$token_name,
            ['*'],
            now()->addMonth()
        );

        return $this->jsonResponse([
            'token' => $token->plainTextToken
        ], true, 201);
    }

    public function logout(): JsonResponse
    {
        $user = auth()->user();

        $user->currentAccessToken()->delete();

        return $this->jsonResponse([
            'message' => 'Logged out successfully'
        ]);
    }
}
