<?php

namespace App\Http\Controllers;

use App\Models\contact;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class ContactController extends ApiController
{
    public function store(Request $request){
        $validatore=Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required',
            'subject'=>'required',
            'body'=>'required',

        ]);
        if($validatore->fails()){
            return $this->errorResponse($validatore->errors(),422);
        }
        $contact= contact::create([

            'name'=>$request->name,
            'email'=>$request->email,
            'subject'=>$request->subject,
            'body'=>$request->body,
        ]);
        return $this->successResponse($contact,201);
    }
}
