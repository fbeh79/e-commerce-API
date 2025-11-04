<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Slider extends Model

{use HasFactory, Notifiable,HasFactory,HasApiTokens;

    protected $table = 'sliders';
    protected $guarded = [];
}
