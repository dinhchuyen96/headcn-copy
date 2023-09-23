<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enum\ERepairTask;
use Illuminate\Support\Facades\DB;
use App\Enum\EOrderDetail;
use App\Enum\EOrder;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'orders';
    protected $fillable = [
        'customer_id', 'created_by', 'updated_by', 'order_no', 'total_items', 'sub_total', 'tax', 'discount', 'total', 'category', 'type', 'status', 'total_money', 'date_payment', 'bill_id', 'supplier_id', 'note', 'model_motorbike_name',
        'created_at'
    ];

    // ===================== ORM Definition START ===================== //

    /**
     * @return BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }
    public function orderDetail()
    {
        return $this->hasOne(OrderDetail::class);
    }



    /**
     * @return BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'customer_id');
    }

    /**
     * @return BelongsTo
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function motorbikes()
    {
        return $this->belongsToMany(Motorbike::class, 'order_details', 'order_id', 'product_id');
    }
    public function motorbike()
    {
        return $this->belongsTo(Motorbike::class, 'motorbikes_id');
    }
    public function repairBill()
    {
        return $this->hasOne(RepairBill::class, 'orders_id');
    }

    public function periodic()
    {
        return $this->hasOne(Periodic::class, 'orders_id');
    }

    public function totalItem()
    {
        $total = 0;
        foreach ($this->details as $item) {
            if ($item->is_atrophy == EOrderDetail::NOT_ATROPHY_ACCESSORY)
                $total += $item->quantity;
        }
        return $total;
    }
    public function totalPrice()
    {
        $total = 0;
        foreach ($this->details as $item) {
            $total += $item->actual_price * $item->quantity;
        }
        return $total;
    }

    public function totalPriceByType()
    {
        $total = 0;
        foreach ($this->details as $item) {
            if ($item->is_atrophy == EOrderDetail::NOT_ATROPHY_ACCESSORY) {
                if ($this->attributes['type'] == EOrderDetail::TYPE_NHAP) {
                    $total += $item->listed_price * $item->quantity;
                } else {
                    $total += $item->actual_price * $item->quantity;
                }
            }
        }
        return $total;
    }
    public function totalPriceForGeneralRepair()
    {
        // Tính toán tổng tiền
        $sumPriceRepair = 0;
        $totalPriceRepairList = RepairTask::where('status', ERepairTask::SAVED)
            ->where('orders_id',  $this->id)
            ->get();
        foreach ($totalPriceRepairList as $key => $item) {
            $total = round($item->price * (100 - $item->promotion) / 100);
            $sumPriceRepair += $total;
        }
        $sumPriceOrderDetail = 0;
        $orderDetail = OrderDetail::where('order_id',  $this->id)
            ->where('status', EOrderDetail::STATUS_SAVED)
            ->where('type', EOrderDetail::TYPE_BANLE)
            ->where('category', EOrder::CATE_REPAIR)
            ->where('is_atrophy', EOrderDetail::NOT_ATROPHY_ACCESSORY)
            ->get();
        foreach ($orderDetail as $key => $item) {
            if ($item->actual_price) {
                $sumPriceOrderDetail += $item->actual_price;
            } else {
                $total = round($item->price * $item->quantity * (100 - $item->promotion) / 100);
                $sumPriceOrderDetail += $total;
            }
        }
        $totalPrice = $sumPriceRepair + $sumPriceOrderDetail;
        return $totalPrice;
    }
    public function totalPriceForKTDK()
    {
        // Tính toán tổng tiền
        $sumPriceRepair = 0;
        $totalPriceRepairList = RepairTask::where('status', ERepairTask::SAVED)
            ->where('orders_id',  $this->id)
            ->get();
        foreach ($totalPriceRepairList as $key => $item) {
            $total = round($item->price * (100 - $item->promotion) / 100);
            $sumPriceRepair += $total;
        }
        $sumPriceOrderDetail = 0;
        $orderDetail = OrderDetail::where('order_id',  $this->id)
            ->where('status', EOrderDetail::STATUS_SAVED)
            ->where('type', EOrderDetail::TYPE_BANLE)
            ->where('category', EOrder::CATE_MAINTAIN)
            ->get();
        foreach ($orderDetail as $key => $item) {
            if ($item->actual_price) {
                $sumPriceOrderDetail += $item->actual_price;
            } else {
                $total = round($item->price * $item->quantity * (100 - $item->promotion) / 100);
                $sumPriceOrderDetail += $total;
            }
        }
        $totalPrice = $sumPriceRepair + $sumPriceOrderDetail;
        return $totalPrice;
    }
    public function totalPriceForOtherService()
    {
        // Tính toán tổng tiền
        $sumPriceRepair = 0;
        $totalPriceOtherServiceList = OtherService::where('order_id',  $this->id)
            ->get();
        foreach ($totalPriceOtherServiceList as $key => $item) {
            $total = round($item->price * (100 - $item->promotion) / 100);
            $sumPriceRepair += $total;
        }
        $sumPriceOrderDetail = 0;
        $orderDetail = OrderDetail::where('order_id',  $this->id)
            ->where('status', EOrderDetail::STATUS_SAVED)
            ->where('type', EOrderDetail::TYPE_BANLE)
            ->where('category', EOrder::CATE_REPAIR)
            ->get();
        foreach ($orderDetail as $key => $item) {
            if ($item->actual_price) {
                $sumPriceOrderDetail += $item->actual_price;
            } else {
                $total = round($item->price * $item->quantity * (100 - $item->promotion) / 100);
                $sumPriceOrderDetail += $total;
            }
        }
        $totalPrice = $sumPriceRepair + $sumPriceOrderDetail;
        return $totalPrice;
    }
    public function totalPriceForTaskRepair()
    {
        // Tính toán tổng tiền
        $sumPriceRepair = 0;
        $totalPriceRepairList = RepairTask::where('status', ERepairTask::SAVED)
            ->where('orders_id',  $this->id)
            ->get();
        foreach ($totalPriceRepairList as $key => $item) {
            $total = round($item->price * (100 - $item->promotion) / 100);
            $sumPriceRepair += $total;
        }
        return $sumPriceRepair;
    }
    public function totalPriceForAccesoryRepair()
    {
        $sumPriceOrderDetail = 0;
        $orderDetail = OrderDetail::where('order_id',  $this->id)
            ->where('status', EOrderDetail::STATUS_SAVED)
            ->where('type', EOrderDetail::TYPE_BANLE)
            ->where('is_atrophy', EOrderDetail::NOT_ATROPHY_ACCESSORY)
            ->where(function ($q) {
                $q->orWhere('category', EOrder::CATE_REPAIR);
                $q->orWhere('category', EOrder::CATE_MAINTAIN);
            })
            ->get();
        foreach ($orderDetail as $key => $item) {
            if ($item->actual_price) {
                $sumPriceOrderDetail += $item->actual_price;
            } else {
                $total = round($item->price * $item->quantity * (100 - $item->promotion) / 100);
                $sumPriceOrderDetail += $total;
            }
        }
        return $sumPriceOrderDetail;
    }


    public function route()
    {
        $route = '';
        if ($this->attributes['type'] == 3) $route = 'phutung.nhapphutung.index';
        else if ($this->attributes['type'] == 1) $route = 'phutung.banbuon.index';
        else if ($this->attributes['type'] == 2) $route = 'phutung.banle.index';
        return $route;
    }
    public function repairTask()
    {
        return $this->hasMany(RepairTask::class, 'orders_id', 'id');
    }


    // ===================== ORM Definition END ===================== //

    public function otherService()
    {
        return $this->hasMany(OtherService::class, 'order_id', 'id');
    }

    public function feeOut()
    {
        return $this->hasMany(FeeOut::class, 'order_id', 'id');
    }
    public function installment()
    {
        return $this->hasOne(Installment::class, 'order_id');
    }
    public function sellBy()
    {
        return $this->belongsTo(User::class, 'seller');
    }
    public function assembleBy()
    {
        return $this->belongsTo(User::class, 'assembler');
    }
    public function fixBy()
    {
        return $this->belongsTo(User::class, 'fixer');
    }
}
