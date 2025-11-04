<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class UserAdreses extends Model
{
    use SoftDeletes, HasFactory;
    protected $table = 'user_adreses';
    protected $guarded = [];
}
