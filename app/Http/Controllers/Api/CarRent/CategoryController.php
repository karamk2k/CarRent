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


    public function show(Category $category){

        return $this->apiResponse(true, 'Category fetched successfully', new CategoryResource($category));
    }


}
