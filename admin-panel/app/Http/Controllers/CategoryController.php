<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends ApiController
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422, 'Validation failed');
        }

        $category = Category::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return $this->successResponse(new CategoryResource($category), 201, 'Category created successfully');
    }

    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422, 'Validation failed');
        }

        $category->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return $this->successResponse(new CategoryResource($category), 201, 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return $this->successResponse(['id' => $category->id], 204, 'Category deleted successfully');
    }

    public function index()
    {
        $categories = Category::with('products')->latest()->get();

        return $this->successResponse(CategoryResource::collection($categories), 201, 'Categories retrieved successfully');
    }
}
