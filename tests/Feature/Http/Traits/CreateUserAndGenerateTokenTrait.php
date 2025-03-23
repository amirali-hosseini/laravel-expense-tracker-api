<?php

namespace Tests\Feature\Http\Traits;

use App\Http\Controllers\AuthController;
use App\Models\User;
use Laravel\Sanctum\NewAccessToken;

trait CreateUserAndGenerateTokenTrait
{
    public function createUser(): User
    {
        return User::factory()->create();
    }

    public function generateToken(User $user): NewAccessToken
    {
        return $user->createToken(AuthController::$token_name);
    }
}