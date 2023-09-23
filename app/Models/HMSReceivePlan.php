<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HMSReceivePlan extends Model
{
    use HasFactory;
    protected $table='hms_receive_plan';
    public function hmsPayment(){
        return $this->hasMany(HMSReceivePlan::class,'hvn_lot_number','id');
    }
}
