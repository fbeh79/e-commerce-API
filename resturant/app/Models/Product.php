<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
class Product extends Model
{

    protected $table = 'products';
    protected $guarded = [];
    protected $appends = ['is_sale'];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getIsSaleAttribute()
    {
        return $this->quantity > 0 &&
            $this->sale_price !== 0 &&
            $this->sale_price !== null &&
            $this->date_on_sale_from < Carbon::now() &&
            $this->date_on_sale_to > Carbon::now();
    }

    public function getStatusAttribute($status)
    {
        switch ($status) {
            case 0:
                $status = 'غیرفعال';
                break;
            case 1:
                $status = 'فعال';
                break;
        }
        return $status;
    }
    public function scopeSearch($query)
    {
        $search = request()->search;
        if (request()->has('search') && trim($search) != '') {
            $query->where('name', 'like', '%' . trim($search) . '%')->Orwhere('description', 'like', '%' . trim($search) . '%');
        }
    }
        public function scopefilter($query)
    {
        if(request()->has('sortBy')){
            $sortBy = request()->sortBy;
            switch($sortBy){
                case 'max':
                    $query->orderBy('price', 'desc');
                    break;
                case 'min':
                    $query->orderBy('price', 'asc');
                    break;
                case 'sale':
                    $query->orderBy('sale_price');
                        break;
                    case'bestseller':
                        $order=Order::where('payment_status',1)->with('products')->get();

                        $products_id=[];
                        foreach($order as $orders){
                            foreach ($orders->products as $product){
                                array_push($products_id,$product->id);
                            }
                        }
                default:
                }
            }
        }

}
