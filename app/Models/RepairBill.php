<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class RepairBill extends Model
{
    use HasFactory;
    protected $table = 'repair_bill';
    protected $fillable = [
        'service_user_id', 'service_user_check_id', 'bill_number', 'in_factory_date', 'km', 'orders_id', 'created_at'
    ];
    public function motorbike()
    {
        return $this->belongsTo(Motorbike::class, 'motorbikes_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_fixer_main');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'orders_id');
    }
    public function serviceUser()
    {
        return $this->belongsTo(User::class, 'service_user_id');
    }
}
