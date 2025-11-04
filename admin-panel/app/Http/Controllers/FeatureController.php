<?php

namespace App\Http\Controllers;

use App\Http\Resources\FeatureResource;
use App\Http\Resources\SliderResource;
use App\Models\Feature;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;
class FeatureController extends ApiController
{
    public function store(Request $request){
        $validatore=Validator::make($request->all(),[
           'title'=>'required',
           'body'=>'required',
           'icon'=>'required',
        ]);
        if($validatore->fails()){
            return $this->errorResponse($validatore->errors(), 400);
        }
        $feature=Feature::create([
            'title'=>$request->title,
            'body'=>$request->body,
            'icon'=>$request->icon
        ]);
        return $this->SuccessResponse(new FeatureResource($feature), 'feature created successfully',201);
    }
    public function index(){
        $features=Feature::latest()->get();
        return $this->SuccessResponse(FeatureResource::collection($features), 'feature retrieved successfully',200);
    }
    public function show(Feature $feature){
        return $this->SuccessResponse(new FeatureResource($feature), 'feature retrieved successfully',200);
    }
    public function update(Request $request, Feature $feature){
        $validatore=Validator::make($request->all(),[
            'title'=>'required',
            'body'=>'required',
            'icon'=>'nullable',
        ]);
        if($validatore->fails()){
            return $this->errorResponse($validatore->errors(), 400);
        }
        $feature->update([
            'title'=>$request->title,
            'body'=>$request->body,
            'icon'=>$request->icon
        ]);
        return $this->SuccessResponse(new FeatureResource($feature), 'feature updated successfully',200);

    }
    public function destroy(Feature $feature){
        $feature->delete();
        return $this->SuccessResponse(new FeatureResource($feature), 'feature deleted successfully',200);
    }
}
