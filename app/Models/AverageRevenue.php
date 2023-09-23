<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AverageRevenue extends Model
{
    use HasFactory;


    protected $table='average_revenue';
    protected $fillable = [
        'month', 'year', 'average_rate',
    ];
}
