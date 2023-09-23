<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseTranferHistory extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'warehouse_tranfer_history';
    protected $fillable = ['from_warehouse_id', 'to_warehouse_id', 'product_id', 'tranfer_type', 'tranfer_date', 'quantity','from_position_in_warehouse_id','to_position_in_warehouse_id'];
    public function sourceWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id', 'id');
    }
    public function destinationWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id', 'id');
    }
}
