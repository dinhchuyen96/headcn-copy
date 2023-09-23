<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enum\ERepairTask;
use Illuminate\Support\Facades\DB;
use App\Enum\EOrderDetail;
use App\Enum\EOrder;

class OrderDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $table='order_details';
    protected $guarded = [];

    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }
    public function accessorie(){
        return $this->belongsTo(Accessory::class,'product_id');
    }
    public function motorbike(){
        return $this->belongsTo(Motorbike::class,'product_id');
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_id');
    }
    public function positioninwarehouse(){
        return $this->belongsTo(PositionInWarehouse::class,'position_in_warehouse_id');
    }

    public function accessory ()
    {
        return $this->belongsTo(Accessory::class,'product_id');
    }
}
