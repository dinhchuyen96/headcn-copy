<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstallmentCompany extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'installment_company';
    protected $fillable = [
        'company_name',
        'company_address',
        'benefit_percentage'

    ];

}
