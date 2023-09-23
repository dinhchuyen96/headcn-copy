<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OtherService extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table = 'other_services';
    protected $fillable = ['order_id', 'list_service_id', 'content', 'price', 'promotion'];

    public function listService()
    {
        return $this->belongsTo(ListService::class, 'list_service_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
