<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GiftPositionInWarehouse extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'gift_position_in_warehouse';

    public function giftwarehouse()
    {
        return $this->belongsTo(GiftWarehouse::class, 'gift_warehouse_id', 'id');
    }
}
