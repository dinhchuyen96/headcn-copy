<?php

namespace App\Models;

use App\Enum\EOrderDetail;
use App\Enum\EWarehouse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class Accessory extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'accessories';
    protected $guarded = [];


    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function positionInWarehouse()
    {
        return $this->belongsTo(PositionInWarehouse::class, 'position_in_warehouse_id', 'id');
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function relatedAccessories ()
    {
        return $this->hasMany(Accessory::class, 'code', 'code');
    }

    public function warehouseTransferHistories ()
    {
        return $this->hasMany(WarehouseTranferHistory::class, 'product_id', 'id')->where('tranfer_type', EWarehouse::PHU_TUNG);
    }

    public function category_accessory()
    {
        return $this->belongsTo(CategoryAccessory::class, 'code', 'code');
    }

    public function orderDetails ()
    {
        return $this->hasMany(OrderDetail::class, 'product_id')
            ->where('status', EOrderDetail::STATUS_SAVED)
            ->whereIn('category', [EOrderDetail::CATE_ACCESSORY, EOrderDetail::CATE_MAINTAIN, EOrderDetail::CATE_REPAIR]);
    }

    public function accessoryChangeLogs ()
    {
        return $this->hasMany(AccessoryChangeLog::class);
    }

    public function getQuantityBuyInputAttribute()
    {
        return $this->orderDetails->where('type', EOrderDetail::TYPE_NHAP)->sum('quantity') + $this->accessoryChangeLogs->where('type', AccessoryChangeLog::NHAP)->sum('accessory_quantity');
    }

    public function getQuantitySellOutputAttribute()
    {
        return $this->orderDetails->whereIn('type', [EOrderDetail::TYPE_BANBUON, EOrderDetail::TYPE_BANLE])->sum('quantity') + $this->accessoryChangeLogs->where('type', AccessoryChangeLog::XUAT)->sum('accessory_quantity');
    }

    public function getPriceInAttribute ()
    {
        return $this->price;
    }

    public function getPriceOutAttribute ()
    {
        $priceOut = $this->orderDetails->whereIn('type', [EOrderDetail::TYPE_BANBUON, EOrderDetail::TYPE_BANLE])->sortByDesc('id')
            ->first();
        return $priceOut ? $priceOut->price : $this->price;
    }

    public function getQuantityInputTransAttribute ()
    {
        $in = 0;
        $relatedAccessories = $this->relatedAccessories;
        foreach ($relatedAccessories as $relatedAccessory) {
            $in += $relatedAccessory->warehouseTransferHistories->where('to_warehouse_id', $this->warehouse_id)
                ->where('to_position_in_warehouse_id', $this->position_in_warehouse_id)
                ->sum('quantity');
        }
        return $in;
    }

    public function getQuantityOutputTransAttribute ()
    {
        $out = 0;
        $relatedAccessories = $this->relatedAccessories;
        foreach ($relatedAccessories as $relatedAccessory) {
            $out += $relatedAccessory->warehouseTransferHistories->where('from_warehouse_id', $this->warehouse_id)
                ->where('from_position_in_warehouse_id', $this->position_in_warehouse_id)
                ->sum('quantity');
        }
        return $out;
    }
}
