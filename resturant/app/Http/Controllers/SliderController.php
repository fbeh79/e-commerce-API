<?php

namespace App\Http\Controllers;

use App\Http\Resources\SliderResource;
use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SliderController extends ApiController
{
    public function index(){
        $sliders = Slider::latest()->get();
        return $this->successResponse(SliderResource::collection($sliders));
    }
}
