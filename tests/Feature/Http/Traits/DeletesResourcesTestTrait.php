<?php

namespace Tests\Feature\Http\Traits;

trait DeletesResourcesTestTrait
{
    public function test_user_can_delete_resource(): void
    {
        $model = $this->model();
        $user = $this->createUser();
        $token = $this->generateToken($user);

        $data = $model::factory()->for($user)->create();

        $response = $this->withToken($token->plainTextToken)
            ->deleteJson('/api/' . $this->route() . '/' . $data->id);

        $response->assertOk()
            ->assertJsonStructure(['success', 'message'])
            ->assertJson(['success' => true]);
        $this->assertDatabaseMissing($model->getTable(), $data->toArray());
    }

    public function test_unauthenticated_user_cannot_delete_resource(): void
    {
        $model = $this->model();

        $data = $model::factory()->create();

        $response = $this->deleteJson('/api/' . $this->route() . '/' . $data->id);

        $response->assertUnauthorized()->assertJsonMissing(['success' => true]);
        $this->assertDatabaseHas($model->getTable(), $data->toArray());
    }
}