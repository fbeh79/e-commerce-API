<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function SuccessResponse($data, $message=null,$code=200){
      return response()->json([
          'status'=>'success',
          'data' => $data,
          'message' => $message,
      ],$code);
    }
    public function ErrorResponse($message=null,$code=400){
        return response()->json([
            'status'=>'error',
            'message' => $message,

        ],$code);
    }
}
