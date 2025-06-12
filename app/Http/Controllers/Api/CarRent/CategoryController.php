<?php

namespace App\Http\Controllers\Api\CarRent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CarRent\CategoryRequest;
use App\Http\Resources\CarRent\CategoryResource;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return $this->apiResponse(true, 'Categories fetched successfully', CategoryResource::collection($categories));
    }

    public function store(CategoryRequest $request){
        $category = Category::create($request->validated());
        return $this->apiResponse(true, 'Category created successfully', new CategoryResource($category));
    }

    public function show(Category $category){
       
        return $this->apiResponse(true, 'Category fetched successfully', new CategoryResource($category));
    }
    public function update(CategoryRequest $request, Category $category){
        $category->update($request->validated());
        return $this->apiResponse(true, 'Category updated successfully', new CategoryResource($category));
    }
    public function destroy(Category $category){
        $category->delete();
        return $this->apiResponse(true, 'Category deleted successfully');
    }

}
