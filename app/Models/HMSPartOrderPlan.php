<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HMSPartOrderPlan extends Model
{
    use HasFactory;
    protected $table='hms_part_order_plan';

    protected $primaryKey = 'order_number';
    public $incrementing = false;
    protected $keyType = 'string';
    // public function details() 
    // {
    //     return $this->hasMany(HMSPartOrderPlanDetail::class, 'order_number', '');
    // }
}
