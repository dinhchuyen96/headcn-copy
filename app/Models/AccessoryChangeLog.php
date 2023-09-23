<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccessoryChangeLog extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'accessory_change_logs';
    protected $fillable = ['id', 'accessory_id', 'accessory_code', 'accessory_quantity', 'warehouse_id', 'position_in_warehouse_id', 'reason', 'description', 'quantity_log', 'type'];
    const NHAP = 1;
    const XUAT = 2;

    public function accessory()
    {
        return $this->belongsTo(Accessory::class, 'accessory_id');
    }

    public function positionInWarehouse()
    {
        return $this->belongsTo(PositionInWarehouse::class, 'position_in_warehouse_id', 'id');
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
}
