<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PositionInWarehouse extends Model
{
    use HasFactory, SoftDeletes;
    protected $table='position_in_warehouse';

    public function warehouse() {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function getWarehouseNameAttribute ()
    {
        return $this->warehouse->name;
    }
}
