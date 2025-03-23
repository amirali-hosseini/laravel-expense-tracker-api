<?php

namespace Tests\Feature\Http\Traits;

trait UpdatesResourcesTestTrait
{
    public function test_user_can_update_resource(): void
    {
        $model = $this->model();
        $user = $this->createUser();
        $token = $this->generateToken($user);

        $data = $model::factory()->for($user)->create();
        $new_data = $model::factory()->for($user)->raw();

        $response = $this->withToken($token->plainTextToken)
            ->patchJson('/api/' . $this->route() . '/' . $data->id, $new_data);

        $response->assertOk()
            ->assertJson(['success' => true]);
        $this->assertDatabaseHas($model->getTable(), $new_data);
    }

    public function test_unauthenticated_user_cannot_update_resource(): void
    {
        $model = $this->model();

        $data = $model::factory()->create();
        $new_data = $model::factory()->raw();

        $response = $this->patchJson('/api/' . $this->route() . '/' . $data->id, $new_data);

        $response->assertUnauthorized()
            ->assertJsonMissing(['success' => true]);
        $this->assertDatabaseHas($model->getTable(), $data->toArray());
    }

    public function test_user_cannot_update_resource_with_empty_data(): void
    {
        $model = $this->model();
        $user = $this->createUser();
        $token = $this->generateToken($user);

        $data = $model::factory()->for($user)->create();

        $response = $this->withToken($token->plainTextToken)
            ->patchJson('/api/' . $this->route() . '/' . $data->id, []);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors([$this->getRequiredFieldName()]);

        $this->assertDatabaseHas($model->getTable(), $data->toArray());
    }
}