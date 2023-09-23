<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellBuyReturn extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'sell_buy_return';

    public function motobike()
    {
        return $this->hasOne(Motorbike::class, 'id', 'product_id');
    }

    public function accessory()
    {
        return $this->hasOne(Accessory::class, 'id', 'product_id');
    }
}
