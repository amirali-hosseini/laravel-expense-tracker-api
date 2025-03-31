<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryControllerTest extends ResourceControllerTestCase
{
    use RefreshDatabase;

    public function model(): Model
    {
        return new Category();
    }

    public function getRequiredFieldName(): string
    {
        return 'name';
    }
}
