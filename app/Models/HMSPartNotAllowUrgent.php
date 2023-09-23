<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HMSPartNotAllowUrgent extends Model
{
    use HasFactory;
    protected $table='hms_parts_not_allow_urgent';

    public function orderPlanDetails()
    {
        return $this->hasOne(HMSPartOrderPlanDetail::class, 'part_no', 'part_no');
    }
}
