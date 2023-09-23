<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Payment;

class Supplier extends Model
{

    use HasFactory;
    use SoftDeletes;
    protected $table = 'suppliers';
    protected $fillable = [
        'name', 'email', 'phone', 'image', 'address', 'created_by', 'updated_by', 'code', 'url', 'province_id', 'district_id', 'ward_id',
    ];

    // ===================== ORM Definition START ===================== //

    /**
     * @return HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    // Dư nợ đầu kỳ
    public function ordersUnPaidBefore($date)
    {
        $countMoneyOrder =  $this->hasMany(Order::class)
            ->where('orders.created_at', '<', $date)
            ->where('orders.order_type', 2)
            ->whereNull('orders.deleted_at')
            ->where(function ($query) {
                $query->where('orders.status', '=', 1);
                $query->orWhere('orders.status', '=', 2);
            })->sum('orders.total_money');
        $countMoneyReceipts = Payment::where('supplier_id', $this->id)
            ->where('payment_date', '<', $date)
            ->sum('money');
        return $countMoneyOrder - $countMoneyReceipts;
    }
    // Số tiền mua hàng trong kỳ
    public function ordersUnPaidDuring($startTime, $endTime)
    {
        return $this->hasMany(Order::class)
            ->where('orders.created_at', '>=', $startTime)
            ->where('orders.created_at', '<=', $endTime)
            ->whereNull('orders.deleted_at')
            ->where('orders.order_type', 2)
            ->where(function ($query) {
                $query->where('orders.status', '=', 1);
                $query->orWhere('orders.status', '=', 2);
            })
            ->sum('orders.total_money');
    }
    // Số tiền đã thanh toán trong kỳ
    public function ordersPaidDuring($startTime, $endTime)
    {
        $countMoneyPayments = Payment::where('supplier_id', $this->id)
            ->where('payment_date', '>=', $startTime)
            ->where('payment_date', '<=', $endTime)
            ->sum('money');
        return $countMoneyPayments;
    }
    // Dư nợ còn phải trả
    public function ordersUnPaid($date)
    {
        $countMoneyOrder =  $this->hasMany(Order::class)
            ->where('orders.created_at', '<', $date)
            ->where('orders.order_type', 2)
            ->whereNull('orders.deleted_at')
            ->where(function ($query) {
                $query->where('orders.status', '=', 1);
                $query->orWhere('orders.status', '=', 2);
            })->sum('orders.total_money');
        $countMoneyReceipts = Payment::where('supplier_id', $this->id)
            ->where('payment_date', '<', $date)
            ->sum('money');
        return $countMoneyOrder - $countMoneyReceipts;
    }

    // lấy các hóa đơn trong 1 khoảng thời gian
    public function ordersDuring($startTime, $endTime)
    {
        return $this->hasMany(Order::class)
            ->where('orders.created_at', '>=', $startTime)
            ->where('orders.created_at', '<=', $endTime)
            ->whereNull('orders.deleted_at')
            ->where('orders.order_type', 2)
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

    /**
     * @return BelongsTo
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


    public function wardSupply()
    {
        return $this->hasOne(Ward::class, 'ward_code', 'ward_id');
    }
    public function districtSupply()
    {
        return $this->hasOne(District::class, 'district_code', 'district_id');
    }
    public function provinceSupply()
    {
        return $this->hasOne(Province::class, 'province_code', 'province_id');
    }

}
