<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = auth()->user()->categories()->latest()->paginate(10);

        return $this->paginatedResponse($categories, CategoryResource::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();

        $validated['user_id'] = auth()->id();

        Category::query()->create($validated);

        return $this->jsonResponse(
            ['message' => 'Category created successfully.'],
            true,
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        if ($category->user()->is(auth()->user())) {

            return $this->jsonResponse(
                ['data' => new CategoryResource($category)]
            );
        } else {

            return $this->unauthenticatedResponse();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        if ($category->user()->is(auth()->user())) {

            $category->update($request->validated());

            return $this->jsonResponse(
                ['message' => 'The category has been updated.']
            );
        } else {

            return $this->unauthenticatedResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->user()->is(auth()->user())) {

            $category->delete();

            return $this->jsonResponse(
                ['message' => 'The category has been deleted.']
            );
        } else {

            return $this->unauthenticatedResponse();
        }
    }
}
