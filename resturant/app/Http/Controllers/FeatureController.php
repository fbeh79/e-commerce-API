<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feature;

class FeatureController extends ApiController
{
    public function index()
    {
        $featurs = Feature::latest()->get();
        return $this->successResponse($featurs);

    }
}
