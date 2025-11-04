<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use App\Models\Order;
class OrderController extends ApiController
{
    public function index()
    {
        $orders=Order::latest()->paginate(4);

        return $this->SuccessResponse([
            'orders'=>OrderResource::collection($orders->load('address')->load('products')),
            'links'=>orderResource::collection($orders->response()->getData()->links),
            'meta'=>orderResource::collection($orders->response()->getData()->meta),
        ]);
    }
}
