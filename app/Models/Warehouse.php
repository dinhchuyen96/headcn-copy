<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes;
    protected $table='warehouse';

    protected $fillable = [
        'id',
        'name',
        'address',
        'established_date',
        'province_id',
        'district_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function positionInWarehouses() {
        return $this->hasMany(PositionInWarehouse::class);
    }

}
