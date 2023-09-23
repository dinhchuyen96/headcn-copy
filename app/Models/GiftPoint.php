<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftPoint extends Model
{
    use HasFactory, SoftDeletes;
    protected $table='gift_point';
}
