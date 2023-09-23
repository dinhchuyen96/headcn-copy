<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;

class RepairTask extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'repair_task';
    protected $fillable = [
        'content', 'price', 'orders_id', 'admin_id', 'status', 'id_fixer_main', 'payment', 'process_company', 'created_at', 'promotion',

    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'id_fixer_main');
    }
    public function workContent()
    {
        return $this->belongsTo(WorkContent::class, 'work_content_id');
    }
    public function supply()
    {
        return $this->belongsTo(Supplier::class, 'supply_id');
    }
    public function orderPayment()
    {
        return $this->belongsTo(Order::class, 'order_payment_id');
    }
}
