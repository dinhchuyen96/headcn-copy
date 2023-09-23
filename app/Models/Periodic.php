<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Periodic extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'periodic_checklist';
    protected $fillable = [
        'head_code', 'km', 'check_date', 'customers_id', 'motorbikes_id', 'orders_id', 'motor_number', 'periodic_level'

    ];
    public function motorbike()
    {
        return $this->belongsTo(Motorbike::class, 'motorbikes_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'orders_id');
    }
    public function serviceUser()
    {
        return $this->belongsTo(User::class, 'service_user_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'service_user_fix_id');
    }
}
