<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Copuon extends Model
{
    use SoftDeletes;
    protected $table = 'copuons';
    protected $guarded = [];
}
