<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'money',
        'note',
        'user_id',
        'receipt_date',
        'type',
        'promotion',
        'account_money_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function accountMoney()
    {
        return $this->belongsTo(AccountMoney::class, 'account_money_id');
    }


}
