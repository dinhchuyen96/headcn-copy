<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GiftWarehouse extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'gift_warehouse';

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

    public function giftPositionInWarehouses()
    {
        return $this->hasMany(GiftPositionInWarehouse::class);
    }
    public function province()
    {
        return $this->hasOne(Province::class, 'province_code', 'province_id');
    }
    public function district()
    {
        return $this->hasOne(District::class, 'district_code', 'district_id');
    }
}
