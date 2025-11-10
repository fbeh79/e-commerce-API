<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function SuccessResponse($data, int $status = 200, string $message = '')
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $status);
    }
    public function ErrorResponse($message=null,$code=400){
        return response()->json([
            'status'=>'error',
            'message' => $message,

        ],$code);
    }
}
