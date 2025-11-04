<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends ApiController
{
    public function index(){
        $about = About::first();
        return $this->successResponse($about,'About updated successfully');
    }
}
