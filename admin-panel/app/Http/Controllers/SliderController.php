<?php

namespace App\Http\Controllers;

use App\Http\Resources\SliderResource;
use App\Models\Slider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SliderController extends ApiController
{
    public function store(Request $request)
    {
        $validatore = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
            'image' => 'required|image',
            'link_title' => 'required|string',
            'link_address' => 'required|string',
        ]);
        if ($validatore->fails()) {
            return $this->ErrorResponse($validatore->messages());
        }
        $imagename = Carbon::now()->microsecond . '_' . $request->image->getClientOriginalName();
        $request->image->storeAs('images/sliders', $imagename);

        $slider = Slider::create([
            'title' => $request->title,
            'body' => $request->body,
            'image' => $imagename,
            'link_title' => $request->link_title,
            'link_address' => $request->link_address,
        ]);
        return $this->SuccessResponse(new SliderResource($slider), 'slider added successfully');
    }

    public function index()
    {
        $sliders = Slider::latest()->get();
        return $this->successResponse(SliderResource::collection($sliders), 'slider list successfully');
    }

    public function show(Slider $slider)
    {
        return $this->successResponse(new SliderResource($slider), 'slider list successfully');
    }

    public function update(Request $request, Slider $slider)
    {
        $validatore = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
            'image' => 'nullable|image',
            'link_title' => 'required|string',
            'link_address' => 'required|string',
        ]);
        if ($validatore->fails()) {
            return $this->ErrorResponse($validatore->messages());
        }
        if ($request->has('image') && $request->image !== null) {
            Storage::delete('images/sliders/' . $slider->image);

            $imagename = Carbon::now()->microsecond . '_' . $request->image->getClientOriginalName();
            $request->image->storeAs('images/sliders', $imagename);

        }
        $slider->update([
            'title' => $request->title,
            'body' => $request->body,
            'image' => $request->image !== null ? $imagename : $slider->image,
            'link_title' => $request->link_title,
            'link_address' => $request->link_address,
        ]);
        return $this->SuccessResponse(new SliderResource($slider), 'slider updated successfully');
    }

    public function destroy(Slider $slider)
    {
        Storage::delete('images/sliders/'.$slider->image);
        $slider->delete();
        return $this->SuccessResponse(new SliderResource($slider), 'slider deleted successfully');
    }
}



