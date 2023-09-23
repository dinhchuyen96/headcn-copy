<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gift extends Model
{
    use HasFactory, SoftDeletes;
    protected $table='gift_point';

    protected $fillable = [
        'gift_name',
        'gift_point',
        'quantity',
    ];

    public function giftWarehouse()
    {
        return $this->hasOne(GiftWarehouse::class, 'id', 'gift_warehouse_id');
    }

}
