<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Installment extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'installment';

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function installmentCompany()
    {
        return $this->belongsTo(InstallmentCompany::class, 'installment_company_id');
    }
}
