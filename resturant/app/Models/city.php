<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\province;

class city extends Model
{
    use HasFactory;
    protected $table = 'cities';
    protected $guarded = [];
    public function provincesCities(){
        return $this->belongsTo('provinces::class');
    }
}
