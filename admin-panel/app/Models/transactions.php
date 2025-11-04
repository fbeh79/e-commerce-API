<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transactions extends Model
{
    protected $table = 'transactions';
    protected $guarded = [];

    public function scopeGetData($query,$month,$status)
    {
  $verta=verta()->startMonth()->subMonth($month-1);
  return $query->where('created_at','>',$verta->toCarbon())->where('status',$status);
    }
}
