<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    public function index(){
        $categories = Category::latest()->paginate(1);
        return CategoryResource::collection($categories);

    }
}

