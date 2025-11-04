<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function successResponse($data=null, $code = 200,$message=null){
        return response()->json([
            'status'=>'success',
            'data'=>$data,
        'message'=>$message,
        ],200);

    }
    public function errorResponse($message = null, $code = 400){
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $code);
    }
}
