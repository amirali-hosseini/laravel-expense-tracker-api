<?php

namespace Tests\Feature\Http\Traits;

use App\Models\Category;

trait CreatesResourcesTestTrait
{
    public function test_user_can_create_resource(): void
    {
        $model = $this->model();
        $user = $this->createUser();
        $token = $this->generateToken($user);

        $factory = $model::factory()->for($user);

        // If the model has a 'category' method, assign a category
        if (method_exists($model, 'category')) {

            $category = Category::factory()->for($user)->create();
            $factory = $factory->for($category);

        }

        $data = $factory->raw();

        $response = $this->withToken($token->plainTextToken)
            ->postJson('/api/' . $this->route(), $data);

        $response->assertCreated()
            ->assertJson(['success' => true]);
        $this->assertDatabaseHas($model->getTable(), $data);
    }

    public function test_unauthenticated_user_cannot_create_resource(): void
    {
        $model = $this->model();

        $data = $model::factory()->raw();

        $response = $this->postJson('/api/' . $this->route(), $data);

        $response->assertUnauthorized()->assertJsonMissing(['success' => true]);
        $this->assertDatabaseMissing($model->getTable(), $data);
    }

    public function test_user_cannot_create_resource_with_empty_data(): void
    {
        $model = $this->model();
        $user = $this->createUser();
        $token = $this->generateToken($user);

        $response = $this->withToken($token->plainTextToken)
            ->postJson('/api/' . $this->route(), []);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors([$this->getRequiredFieldName()]);

        $this->assertDatabaseMissing($model->getTable(), []);
    }
}