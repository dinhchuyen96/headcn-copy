<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'customer_id',
        'money',
        'note',
        'user_id',
        'payment_date',
        'type',
        'account_money_id',
        'service_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    public function accountMoney()
    {
        return $this->belongsTo(AccountMoney::class, 'account_money_id');
    }
}
