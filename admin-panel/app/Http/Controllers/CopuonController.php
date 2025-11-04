<?php

namespace App\Http\Controllers;

use App\Http\Resources\CouponResource;
use App\Models\Copuon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class CopuonController extends ApiController
{
    public function store(Request $request){
        $validate=Validator::make($request->all(),[
            'code'=>'required',
            'percentage'=>'required',
            'expires_at'=>'required',
            ]);
        if($validate->fails()){
            return $this->errorResponse($validate->errors(),422);
        }
    $copuon=Copuon::create([
        'code'=>$request->code,
        'percentage'=>$request->percentage,
        'expires_at'=>$request->expires_at

    ]);
        return $this->successResponse($copuon,201);

    }

    public function index()
    {
        $coupons=Copuon::latest()->get();
        return $this->successResponse(CouponResource::collection($coupons),200);
    }
    public function show(copuon $copuon){
        return $this->successResponse(new CouponResource($copuon),200);
    }
    public function update(Request $request, copuon $copuon){
        $validate=Validator::make($request->all(),[
            'code'=>'required,code,'.$copuon->id,
            'percentage'=>'required',
            'expires_at'=>'required',
        ]);
        if($validate->fails()){
            return $this->errorResponse($validate->errors(),422);
        }
        $copuon->update::create([
            'code'=>$request->code,
            'percentage'=>$request->percentage,
            'expires_at'=>$request->expires_at

        ]);
        return $this->successResponse($copuon,200);

    }
    public function destroy(copuon $copuon){
        $copuon->delete();
        return $this->successResponse($copuon,'the cupon deleted',);
    }
}
