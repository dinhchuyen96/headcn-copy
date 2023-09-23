<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HMSPartOrderPlanDetail extends Model
{
    use HasFactory;
    protected $table='hms_part_order_plan_detail';

    public function orderPlan()
    {
        return $this->belongsTo(HMSPartOrderPlan::class, 'order_number', 'order_number');
    }
}
