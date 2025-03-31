<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Http\Traits\CreateUserAndGenerateTokenTrait;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase, CreateUserAndGenerateTokenTrait;

    public function test_user_can_login(): void
    {
        $user = $this->createUser();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonStructure(
                ['success', 'token']
            )->assertJson(
                ['success' => true]
            );

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
    }

    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password#12345',
        ]);

        $response->assertCreated()
            ->assertJsonStructure(
                ['success', 'token']
            )->assertJson(
                ['success' => true]
            );

        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
        ]);

        $user = User::where('email', 'johndoe@example.com')->first();

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
    }

    public function test_user_can_logout(): void
    {
        $user = $this->createUser();
        $token = $this->generateToken($user);

        $tokenId = $token->accessToken->id;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token->plainTextToken
        ])->postJson('/api/logout');

        $response->assertOk()->assertJson([
            'success' => true
        ]);
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'id' => $tokenId,
        ]);
    }
}
