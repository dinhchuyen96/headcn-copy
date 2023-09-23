<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GiftTransactionOrder extends Model
{
    use HasFactory, SoftDeletes;
    protected $table='gift_transactions_orders';
}
