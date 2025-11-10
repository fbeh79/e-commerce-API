<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors());
        }


        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->errorResponse('User not found');
        }

        if (!Hash::check($request->password, $user->password)) {
            return $this->errorResponse('Wrong password');
        }


        $token = $user->createToken('Token', ['admin'])->plainTextToken;


        return $this->successResponse([
            'token' => $token,
            'user' => $user,
        ]);
    }
    public function logout(){
       auth()->user()->tokens()->delete();
       return $this->successResponse('logged out');
    }
 }
