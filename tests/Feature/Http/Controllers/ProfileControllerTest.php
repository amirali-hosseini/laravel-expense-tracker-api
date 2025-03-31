<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
