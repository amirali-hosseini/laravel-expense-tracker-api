<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token'
        ]);
    }

    public function test_user_can_register(): void
    {
        $user = User::factory()->make();

        $response = $this->post('/api/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token'
        ]);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth-token', ['*'], now()->addHour());

        $tokenId = $token->accessToken->id;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token->plainTextToken
        ])->post('/api/logout');

        $response->assertOk()->assertJson([
            'result' => true
        ]);
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'id' => $tokenId,
        ]);
    }
}
