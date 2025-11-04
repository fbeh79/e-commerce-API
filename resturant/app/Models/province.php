<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class province extends Model
{
    protected $guarded = [];
    protected  $table='provinces';
    public function cities() {
        return $this->hasMany(City::class);
    }

}
