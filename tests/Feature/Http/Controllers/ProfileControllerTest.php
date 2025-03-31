<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\Feature\Http\Traits\CreateUserAndGenerateTokenTrait;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase, CreateUserAndGenerateTokenTrait;

    public function test_user_can_view_profile(): void
    {
        $user = $this->createUser();
        $token = $this->generateToken($user);

        $response = $this->withToken($token->plainTextToken)
            ->getJson('/api/profile');

        $response->assertOk()
            ->assertJsonStructure(['success', 'data'])
            ->assertJson(['success' => true]);

        $info = $response->json('data');

        $this->assertArrayHasKey('name', $info);
        $this->assertArrayHasKey('email', $info);
        $this->assertArrayHasKey('created_at', $info);
        $this->assertArrayHasKey('updated_at', $info);
    }

    public function test_user_can_update_profile(): void
    {
        $user = $this->createUser();
        $token = $this->generateToken($user);

        $new_data = [
            'name' => 'John Doe',
            'email' => fake()->unique()->safeEmail()
        ];

        $response = $this->withToken($token->plainTextToken)
            ->patchJson('/api/profile', $new_data);

        $response->assertOk()
            ->assertJson(['success' => true, 'message' => 'Profile has been updated.']);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $new_data['name'],
            'email' => $new_data['email']
        ]);
    }

    public function test_user_can_change_password(): void
    {
        $user = $this->createUser();
        $token = $this->generateToken($user);

        $new_password = Str::password(12);

        $data = [
            'current_password' => 'password',
            'password' => $new_password,
            'password_confirmation' => $new_password,
        ];

        $response = $this->withToken($token->plainTextToken)
            ->patchJson('/api/profile/password', $data);

        $response->assertOk()
            ->assertJson(['success' => true, 'message' => 'Password has been updated.']);

        $response2 = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $new_password,
        ]);

        $response2->assertOk()
            ->assertJsonStructure(['success', 'token'])
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
    }
}
