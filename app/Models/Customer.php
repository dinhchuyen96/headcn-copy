<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Receipt;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use App\Enum\EOrder;

class Customer extends Model
{

    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'address',
        'image',
        'code',
        'sex',
        'district',
        'city',
        'ward',
        'identity_code',
        'job',
        'birthday',
        'country'

    ];

    // ===================== ORM Definition START ===================== //

    /**
     * @return HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    // Dư nợ đầu kỳ
    public function ordersUnPaidBefore($date)
    {
        $countMoneyOrder =  $this->hasMany(Order::class)
            ->where('orders.created_at', '<', $date)
            ->where('orders.order_type', 1)
            ->where('orders.category', '<>', EOrder::OTHER)
            ->whereNull('orders.deleted_at')
            ->where(function ($query) {
                $query->where('orders.status', '=', 1);
                $query->orWhere('orders.status', '=', 2);
            })->sum('orders.total_money');
        $countMoneyReceipts = Receipt::where('customer_id', $this->id)
            ->where('receipt_date', '<', $date)
            ->sum('money');
        return $countMoneyOrder - $countMoneyReceipts;
    }
    // Số tiền mua hàng trong kỳ
    public function ordersUnPaidDuring($startTime, $endTime)
    {
        return $this->hasMany(Order::class)
            ->where('orders.created_at', '>=', $startTime)
            ->where('orders.created_at', '<=', $endTime)
            ->where('orders.category', '<>', EOrder::OTHER)
            ->whereNull('orders.deleted_at')
            ->where('orders.order_type', 1)
            ->where(function ($query) {
                $query->where('orders.status', '=', 1);
                $query->orWhere('orders.status', '=', 2);
            })
            ->sum('orders.total_money');
    }
    // Số tiền đã thanh toán trong kỳ
    public function ordersPaidDuring($startTime, $endTime)
    {
        $countMoneyReceipts = Receipt::where('customer_id', $this->id)
            ->where('receipt_date', '>=', $startTime)
            ->where('receipt_date', '<=', $endTime)
            ->sum('money');
        return $countMoneyReceipts;
    }
    // Dư nợ còn phải trả
    public function ordersUnPaid($date)
    {
        $countMoneyOrder =  $this->hasMany(Order::class)
            ->where('orders.created_at', '<', $date)
            ->where('orders.order_type', 1)
            ->where('orders.category', '<>', EOrder::OTHER)
            ->whereNull('orders.deleted_at')
            ->where(function ($query) {
                $query->where('orders.status', '=', 1);
                $query->orWhere('orders.status', '=', 2);
            })->sum('orders.total_money');
        $countMoneyReceipts = Receipt::where('customer_id', $this->id)
            ->where('receipt_date', '<', $date)
            ->sum('money');
        return $countMoneyOrder - $countMoneyReceipts;
    }


    // lấy các hóa đơn trong 1 khoảng thời gian
    public function ordersDuring($startTime, $endTime)
    {
        return $this->hasMany(Order::class)
            ->where('orders.created_at', '>=', $startTime)
            ->where('orders.created_at', '<=', $endTime)
            ->where('orders.category', '<>', EOrder::OTHER)
            ->whereNull('orders.deleted_at')
            ->where('orders.order_type', 1)
            ->where(function ($query) {
                $query->where('orders.status', '=', 1);
                $query->orWhere('orders.status', '=', 2);
            });
    }

    /**
     * @return BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function motorbikes()
    {
        return $this->hasMany(Motorbike::class, 'customer_id');
    }

    /**
     * @return BelongsTo
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function periodics()
    {
        return $this->hasMany(Periodic::class, 'customers_id');
    }

    public function wardCustomer()
    {
        return $this->hasOne(Ward::class, 'ward_code', 'ward');
    }
    public function districtCustomer()
    {
        return $this->hasOne(District::class, 'district_code', 'district');
    }
    public function provinceCustomer()
    {
        return $this->hasOne(Province::class, 'province_code', 'city');
    }
    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'customer_id');
    }

    public function contactHistories()
    {
        return $this->hasMany(ContacHistory::class, 'customer_id');
    }
    // ===================== ORM Definition END ===================== //
}
