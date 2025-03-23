<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Tests\Feature\Http\Traits\CreateUserAndGenerateTokenTrait;
use Tests\Feature\Http\Traits\CreatesResourcesTestTrait;
use Tests\Feature\Http\Traits\DeletesResourcesTestTrait;
use Tests\Feature\Http\Traits\ListsResourcesTestTrait;
use Tests\Feature\Http\Traits\UpdatesResourcesTestTrait;
use Tests\Feature\Http\Traits\ViewsResourcesTestTrait;
use Tests\TestCase;

abstract class ResourceControllerTestCase extends TestCase
{
    use ListsResourcesTestTrait,
        CreatesResourcesTestTrait,
        ViewsResourcesTestTrait,
        UpdatesResourcesTestTrait,
        DeletesResourcesTestTrait,
        CreateUserAndGenerateTokenTrait;

    // The object of model
    abstract public function model(): Model;

    // The name of one of required fields
    abstract public function getRequiredFieldName(): string;

    public function route(): string
    {
        return $this->model()->getTable();
    }
}
