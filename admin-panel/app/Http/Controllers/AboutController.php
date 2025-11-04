<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AboutController extends ApiController
{
    public function index(){
        $item=About::firstOrFail();
        return $this->successResponse($item);
    }
    public function update(Request $request ,About $about){
        $validator=Validator::make($request->all(),[
            'title'=>'required',
            'description'=>'required',
            'link'=>'string'
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->errors());
        }
            $about->update([
                'title'=>$request->title,
                'description'=>$request->description,
                'link'=>$request->link,
            ]);
        return $this->successResponse($about,'About updated successfully');
    }
}
