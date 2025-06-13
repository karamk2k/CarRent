<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CarRent\CategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories.index', [
            'categories' => Category::latest()->paginate(10)
        ]);
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $category = Category::create($validated);

        return $this->apiResponse(
            success: true,
            message: 'Category created successfully',
            data: $category
        );
    }

    public function edit(Category $category): JsonResponse
    {
        return $this->apiResponse(
            success: true,
            data: $category
        );
    }

    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        $validated = $request->validated();

        $category->update($validated);

        return $this->apiResponse(
            success: true,
            message: 'Category updated successfully',
            data: $category
        );
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return $this->apiResponse(
            success: true,
            message: 'Category deleted successfully'
        );
    }
}
