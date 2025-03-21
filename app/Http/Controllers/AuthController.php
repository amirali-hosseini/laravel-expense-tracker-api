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

            return response()->json([
                'token' => $token->plainTextToken
            ]);

        } else {

            return response()->json(
                [
                    'errors' => [
                        'email' => ['The provided credentials are incorrect.']
                    ],
                ],
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

        return response()->json([
            'token' => $token->plainTextToken
        ]);
    }
}
