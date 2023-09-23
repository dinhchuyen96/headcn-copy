<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GiftLog extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'gift_log';

    public function gift()
    {
        return $this->hasOne(Gift::class, 'id', 'gift_point_id');
    }
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
}
