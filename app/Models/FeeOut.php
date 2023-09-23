<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeOut extends Model
{
    use HasFactory;
    protected $table = 'fee_out';
    protected $fillable = ['order_id', 'list_service_id', 'content', 'price'];

    public function listService()
    {
        return $this->belongsTo(ListService::class, 'list_service_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }
}
