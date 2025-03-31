<?php

namespace Tests\Feature\Http\Traits;

trait ListsResourcesTestTrait
{
    public function test_user_can_view_resource_list(): void
    {
        $model = $this->model();
        $user = $this->createUser();
        $token = $this->generateToken($user);

        $model::factory(10)->for($user)->create();

        $response = $this->withToken($token->plainTextToken)
            ->getJson('/api/' . $this->route());

        $response->assertOk()
            ->assertJsonStructure(['success', 'data'])
            ->assertJson(['success' => true])
            ->assertJsonCount(10, 'data');

        $data = $response->json('data');
        $firstItem = $data[0];

        $this->assertArrayHasKey('id', $firstItem);
        $this->assertArrayHasKey('created_at', $firstItem);
        $this->assertArrayHasKey('updated_at', $firstItem);
    }

    public function test_unauthenticated_user_cannot_view_resource_list(): void
    {
        $response = $this->getJson('/api/' . $this->route());

        $response->assertUnauthorized()->assertJsonMissing(['success' => true]);
    }
}