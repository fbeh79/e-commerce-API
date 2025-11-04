<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class OrderItem extends Model
{
    use softDeletes,HasApiTokens;
    protected $guarded = [];
    protected $table = 'order_items';
}
