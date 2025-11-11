<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{
    public function index(){
        $users = User::latest()->paginate(3);

        return $this->SuccessResponse([
            'users' => UserResource::collection($users),
            'links' => $users->links(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ]
        ]);
    }
    public function show(User $user){
        return $this->SuccessResponse(new UserResource($user));
    }
    public function store(Request $request){
        $valid=Validator::make($request->all(),[
            'name'=>'required|string',
            'email'=>'required|email|unique:users',
            'password'=>'required|string',
            'cell_phone'=>'required',
            'role_ids'=>'required',
            'role_ids.*'=>'integer|exists:roles,id',
        ]);

        if($valid->fails()){
            return $this->ErrorResponse($valid->errors());
        }
        DB::beginTransaction();
        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'cell_phone'=>$request->cell_phone,
        ]);
        $user->roles()->attach($request->role_ids);
        DB::commit();

        return $this->SuccessResponse(new UserResource($user));
    }
    public function update(Request $request,User $user){
        $valid=Validator::make($request->all(),[
            'name'=>'required|string',
            'email'=>'required|email',
            'password'=>'required|string',
            'cell_phone'=>'required',
        ]);


        if($valid->fails()){
            return $this->ErrorResponse($valid->errors());
        }
        DB::beginTransaction();
        $user->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=> $request->has($request->password)? Hash::make($request->password):$user->password,
            'cell_phone'=>$request->cell_phone,
        ]);
        $user->roles()->sync($request->role_ids);
        DB::commit();
        return $this->SuccessResponse(new UserResource($user),'User has been updated');
    }

}
