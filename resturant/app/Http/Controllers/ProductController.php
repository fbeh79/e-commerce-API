<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ProductController extends ApiController
{
    public function index()
    {
        $products = Product::where('status', 1)->latest()->paginate(2);

        return $this->successResponse([
            'products' => ProductResource::collection($products->load('category')),
            'links' => ProductResource::collection($products)->response()->getdata()->links,
            'meta' => ProductResource::collection($products)->response()->getdata()->meta,

        ]);
    }

    public function randomProduct(Request $request)
    {
        $random = Validator::make($request->all(), [
            'count' => 'required|integer|min:1',
        ]);
        if ($random->fails()) {
            return $this->errorResponse($random->errors());
        }
        $product = Product::where('quantity', '>', 0)->get()->random($request->count);
        return $this->successResponse([
            'product' => productResource::collection($product->load('category')),
        ]);
    }

    public function productsTabs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'categories' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors());
        }

        $categoriesId = explode(',', $request->get('categories'));
        $categories = Category::whereIn('id', $categoriesId)->get();

        $tabPanel = [];

        foreach ($categories as $category) {

            $products = $category->products()
                ->where('status', 1)
                ->where('quantity', '>', 0)
                ->get();


            $tabPanel[] = ProductResource::collection($products);
        }

        return $this->successResponse([
            'tablist' => $categories->pluck('name'),
            'tabpanel' => $tabPanel,
        ]);
    }
    public function menu(Request $request){
        $products=Product::where('status',1)->where('quantity','>',0)->filter()->search()->paginate(3);
        return $this->successResponse([
            'products' => ProductResource::collection($products->load('category')),
            'links' => ProductResource::collection($products)->response()->getdata()->links,
            'meta' => ProductResource::collection($products)->response()->getdata()->meta,
        ]);
    }
}
