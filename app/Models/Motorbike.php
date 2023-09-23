<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Motorbike extends Model
{
    use HasFactory;

    protected $fillable = [
        'chassic_no',
        'engine_no',
        'model_code',
        'color',
        'quantity',
        'price',
        'supplier_id',
        'customer_id',
        'status',
        'buy_date',
        'warehouse_id',
        'admin_id',
        'sell_date',
        'order_id',
        'model_list',
        'model_type',
        'head_sell',
        'motor_numbers',
        'head_get',
        'is_out',
        'customer_phone'
    ];

    public function Supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function mtoc()
    {
        return $this->belongsTo(Mtoc::class, 'mtoc_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function repairBills()
    {
        return $this->hasMany(RepairBill::class, 'motorbikes_id');
    }
    public function periodics()
    {
        return $this->hasMany(Periodic::class, 'motorbikes_id');
    }
    public function motorNumberCustomer()
    {
        return $this->hasOneThrough(
            Order::class,
            OrderDetail::class,
            'product_id',
            'id',
        );
    }

    public function orderDetail()
    {
        return $this->hasOne(OrderDetail::class, 'product_id');
    }

    public function tranferWarehouse()
    {
        return $this->hasOne(WarehouseTranferHistory::class, 'product_id')->where('tranfer_type', 0);
    }
}
