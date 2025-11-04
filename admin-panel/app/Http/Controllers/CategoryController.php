<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends ApiController
{
    public function store(Request $request){
        $validator=Validator::make($request->all(),[
            'name'=>'required|string',
            'status'=>'required',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->errors());
        }
        $category=Category::create([
            'name'=>$request->name,
            'status'=>$request->status
        ]);
        return $this->successResponse(new CategoryResource($category),201);
    }


    public function update(Request $request,category $category)
    {
        $validator=Validator::make($request->all(),[
            'name'=>'required|string',
            'status'=>'required',
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->errors());
        }
        $category->update([
            'name'=>$request->name,
            'status'=>$request->status
        ]);
        return $this->successResponse(new CategoryResource($category),201);
    }
    public function destroy(category $category){
      $item= $category->delete();
      return $this->successResponse(new CategoryResource($category),201);
    }
    public function index(category $category)
    {
        $item = category::latest()->get();
        return $this->successResponse(CategoryResource::collection($item->load('products')), 201);
    }


}
