<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContacHistory extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'contact_history';

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    public function contactMethod()
    {
        return $this->belongsTo(ContacMethod::class, 'contact_method_id', 'id');
    }
}
