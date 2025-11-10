<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends ApiController
{
    public function index(){
        $user=Role::latest()->get();
        return $this->successResponse($user);
    }
    public function show(Role $role){
        return $this->successResponse($role);
    }
    public function store(Request $request){
        $validate=Validator::make($request->all(),[
            'name'=>'required',
        ]);
        if($validate->fails()){
            return $this->errorResponse($validate->errors());
        }
        $role=Role::create([
            'name'=>$request->name
        ]);
        return $this->successResponse($role);

    }
    public function update(Request $request,Role $role){
        $validate=Validator::make($request->all(),[
            'name'=>'required',
        ]);
        if($validate->fails()){
            return $this->errorResponse($validate->errors());
        }
        $role->update([
            'name'=>$request->name
        ]);
        return $this->successResponse($role);
    }
}
