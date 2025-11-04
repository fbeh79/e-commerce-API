<?php

namespace App\Http\Controllers;

use App\Models\FooterSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FooterSectionController extends ApiController
{
    public function index()
    {
        $FooterSection = FooterSection::firstOrFail();
        return $this->SuccessResponse('success',$FooterSection);
    }

public function update(Request $request,FooterSection $footerSection){
    $validator=Validator::make($request->all(),[
        'title'=>'nullable',
        'description'=>'nullable',
        'phone_number'=>'nullable',
        'address'=>'nullable',
        'email'=>'nullable',
        'facebook'=>'nullable',
        'telegram'=>'nullable',
        'instagram'=>'nullable'
    ]);
    if($validator->fails()){
        return $this->errorResponse($validator->errors()->first(),422);
}
    $footerSection->update([
        'title'=>$request->title,
        'description'=>$request->description,
        'phone_number'=>$request->phone_number,
        'address'=>$request->address,
        'email'=>$request->email,
        'facebook'=>$request->facebook,
        'telegram'=>$request->telegram,
        'instagram'=>$request->instagram,

    ]);
    return $this->successResponse($footerSection,'Success',200);
}
}
