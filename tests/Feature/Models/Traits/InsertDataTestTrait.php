<?php

namespace Tests\Feature\Models\Traits;

use Illuminate\Database\Eloquent\Model;

trait InsertDataTestTrait
{
    abstract function model(): Model;

    public function additionalParams(): array
    {
        return [];
    }

    public function test_insert_data(): void
    {
        $model = $this->model();

        $data = $model->factory()->create()->toArray();

        $this->assertDatabaseHas($model->getTable(), $data);
    }
}