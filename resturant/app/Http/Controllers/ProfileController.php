<?php

namespace App\Http\Controllers;
use App\Http\Resources\OrderResource;
use App\Http\Resources\TransActionResource;
use App\Http\Resources\UserAddressesResource;
use App\Http\Resources\WishlistResource;
use App\Models\UserAdress;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Validator;
use App\Models\city;
use App\Models\province;
use Illuminate\Http\Request;


class ProfileController extends ApiController
{

    public function provincesCities(){


return $this->successResponse([
    'cities'=>city::all(),
    'provinces'=>province::all()
]);

    }
    public function userAddress(Request $request){

        $validator = Validator::make($request->all(), [
            'title' => 'required|string' ,
            'cell_phone' => ['required' , 'regex:/^09[0|1|2|3][0-9]{8}$/'] ,
            'postal_code' => ['required' , 'regex:/^\d{5}[ -]?\d{5}$/'] ,
            'province_id' => 'required|integer' ,
            'city_id' => 'required|integer' ,
            'address' => 'required|string',
            'user_id'=>'required|integer' ,
        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->errors());
        }
        $Address=UserAdress::create([
            'title' => $request->title ,
            'cell_phone' =>  $request->cell_phone,
            'postal_code' => $request->postal_code ,
            'province_id' => $request->province_id ,
            'city_id' => $request->city_id ,
            'address' => $request->address,
            'user_id'=>$request->user_id
        ]);
return $this->successResponse(['create address successfully']);
    }
    public function updateAddress(Request $request,UserAdress $address){

        $validator = Validator::make($request->all(), [
            'title' => 'required|string' ,
            'cell_phone' => ['required' , 'regex:/^09[0|1|2|3][0-9]{8}$/'] ,
            'postal_code' => ['required' , 'regex:/^\d{5}[ -]?\d{5}$/'] ,
            'province_id' => 'integer' ,
            'city_id' => 'required|integer' ,
            'address' => 'required|string',

        ]);
        if($validator->fails()){
            return $this->errorResponse($validator->errors());
        }
        $address->update([
            'title' => $request->title ,
            'cell_phone' =>  $request->cell_phone,
            'postal_code' => $request->postal_code ,
            'province_id' => $request->province_id ,
            'city_id' => $request->city_id ,
            'address' => $request->address,

        ]);
        return $this->SuccessResponse('update address successfully');
    }
    public function indexAddress(){
$addresses=auth()->user()->addresses()->get();
return $this->successResponse(UserAddressesResource::collection($addresses->load('province')->load('city')));
    }

        public function AddToWishlist(Request $request){
     $validate=Validator::make($request->all(),[
         'product_id'=>'required|integer|exist:products,id',
     ]);
     if($validate->fails()){
         return $this->errorResponse($validate->errors());
     }
     $item=Wishlist::where('user_id',auth()->id())->where('product_id',$request->product_id)->first();
     if ($item) {
         return $this->errorResponse('product already added to wishlist');
     }else{
         Wishlist::create([
             'user_id'=>auth()->id(),
             'product_id'=>$request->product_id
         ]);
     }

return $this->SuccessResponse('add to wishlist successfully');
        }

    public function deleteWishlist(Wishlist $wishlist){
        $wishlist->delete();
        return $this->SuccessResponse('wishlist deleted successfully');
    }

    public function indexWishlist()
    {
        $wishlist=auth()->user()->wishlist()->get();
        return $this->successResponse(WishlistResource::collection($wishlist->loade('product')));
    }

    public function orders()
    {
        $orders=auth()->user()->orders()->latest()->paginate(2);

        return $this->successResponse([
            'products'=>OrderResource::collection($orders->load('address')->load('product')),
            'link'=>OrderResource::collection($orders)->response()->getData->links(),
            'meta'=>OrderResource::collection($orders)->response()->getData->meta(),
        ]);
    }

    public function transactions()
    {
        $transactions=auth()->user()->transactions()->latest()->paginate(2);

        return $this->successResponse([
            'products'=>TransActionResource::collection($transactions),
            'link'=>TransActionResource::collection($transactions)->response()->getData->links(),
            'meta'=>TransActionResource::collection($transactions)->response()->getData->meta(),
        ]);
    }

}
