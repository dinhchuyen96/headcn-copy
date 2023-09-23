<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountMoney extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'account_money';
}
