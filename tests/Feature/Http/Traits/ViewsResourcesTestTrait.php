<?php

namespace Tests\Feature\Http\Traits;

trait ViewsResourcesTestTrait
{
    public function test_user_can_view_a_resource(): void
    {
        $model = $this->model();
        $user = $this->createUser();
        $token = $this->generateToken($user);

        $data = $model::factory()->for($user)->create();

        $response = $this->withToken($token->plainTextToken)
            ->getJson('/api/' . $this->route() . '/' . $data->id);

        $response->assertOk()
            ->assertJsonStructure(['success', 'data'])
            ->assertJson(['success' => true]);
        $this->assertDatabaseHas($model->getTable(), $data->toArray());
    }

    public function test_unauthenticated_user_cannot_view_a_resource(): void
    {
        $model = $this->model();

        $data = $model::factory()->create();

        $response = $this->getJson('/api/' . $this->route() . '/' . $data->id);

        $response->assertUnauthorized()->assertJsonMissing(['success' => true])->assertJsonMissingPath('data');
    }
}