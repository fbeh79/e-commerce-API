<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\FooterSection;

class FooterSectionController extends ApiController
{
    public function store(Request $request){
        $validator=Validator::make($request->all(),[
            'title'=>'required',
            'description'=>'required',
            'phone_number'=>'required',
            'address'=>'required',
            'email'=>'required',
            'facebook'=>'required',
            'telegram'=>'required',
            'instagram'=>'required'
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->errors()->first(),422);
        }
        $item=FooterSection::create([
            'title'=>$request->title,
            'description'=>$request->description,
            'phone_number'=>$request->phone_number,
            'address'=>$request->address,
            'email'=>$request->email,
            'facebook'=>$request->facebook,
            'telegram'=>$request->telegram,
            'instagram'=>$request->instagram,

        ]);
        return $this->successResponse($item,'Success',200);

    }
}
