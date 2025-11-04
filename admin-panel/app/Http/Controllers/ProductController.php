<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;



class ProductController extends ApiController
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'primary_image' => 'required',
            'name' => 'required|string',
            'category_id' => 'required|integer',
            'description' => 'required',
            'price' => 'required|integer',
            'status' => 'integer',
            'quantity' => 'required|integer',
            'sale_price' => 'nullable|integer',
            'date_on_sale_from' => 'nullable',
            'date_on_sale_to' => 'nullable',
            'images.*' => 'nullable|image|max:500'
        ]);
        if ($validator->fails()) {
            return $this->ErrorResponse($validator->errors(), 422);
        }
        $imageName = Carbon::now()->microsecond . '_' . $request->primary_image->getClientOriginalName();
        $request->primary_image->storeAs('images/products', $imageName);

        if ($request->has('images') && $request->images !== null) {
            $fileNames = [];
            foreach ($request->images as $image) {
                $imageName = Carbon::now()->microsecond . '_' . $request->image->getClientOriginalName();
                $request->image->storeAs('images/products', $imageName);
                array_push($fileNames, $imageName);
            }
        }
        $product = Product::create([
            'primary_image' => $imageName,
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'status' => $request->status,
            'quantity' => $request->quantity,
            'sale_price' => $request->sale_price,
            'date_on_sale_from' => $request->date_on_sale_from,
            'date_on_sale_to' => $request->date_on_sale_to,
            'images' => $request->images,
        ]);
        return $this->successResponse($product);

    }

    public function index()
    {
        $products = Product::latest()->get();
        return $this->successResponse(ProductResource::collection($products->load('category')), 200);
    }

    public function show(Product $product)
    {
        return $this->successResponse(new ProductResource($product->load('category')), 200);
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'primary_image' => 'nullable|image|max:500',
            'name' => 'required|string',
            'category_id' => 'required|integer',
            'description' => 'required|string',
            'price' => 'required|integer',
            'status' => 'required|integer',
            'quantity' => 'required|integer',
            'sale_price' => 'nullable|integer',
            'date_on_sale_from' => 'nullable|date_format:Y/m/d H:i:s',
            'date_on_sale_to' => 'nullable|date_format:Y/m/d H:i:s',
            'images.*' => 'nullable|image|max:500'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        if ($request->has('primary_image') && $request->primary_image !== null) {
            // Storage::delete(env('PRODUCT_IMAGES_UPLOAD_PATH') . $product->primary_image);

            $primaryImageName = Carbon::now()->microsecond . '_' . $request->primary_image->getClientOriginalName();
            $request->primary_image->storeAs('images/products', $primaryImageName);
        }

        if ($request->has('images') && $request->images !== null) {
            $fileNameImages = [];
            foreach ($request->images as $image) {
                $imageName = Carbon::now()->microsecond . '_' . $image->getClientOriginalName();
                $image->storeAs('images/products', $imageName);
                array_push($fileNameImages, $imageName);
            }
        }
        // dd($primaryImageName, $fileNameImages);

        DB::beginTransaction();

        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'primary_image' => $request->primary_image !== null ? $primaryImageName : $product->primary_image,
            'description' => $request->description,
            'status' => $request->status,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'sale_price' => $request->sale_price,
            'date_on_sale_from' => $request->date_on_sale_from,
            'date_on_sale_to' => $request->date_on_sale_to,
        ]);

        if ($request->has('images') && $request->images !== null) {
            foreach ($product->images as $productImage) {
                // Storage::delete(env('PRODUCT_IMAGES_UPLOAD_PATH') . $productImage->image);
                $productImage->delete();
            }

        }

        DB::commit();

        return $this->successResponse(new ProductResource($product->load('category')->load('images')));
    }

}
