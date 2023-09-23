<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $table = 'ex_district';
    protected $fillable = ['district_code', 'created_by', 'created_date', 'last_modified_by', 'last_modified_date', 'description', 'name', 'province_code', 'short_name', 'status', 'type'];

    public function wards()
    {
        return $this->hasMany(Ward::class, 'district_code', 'district_code');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'province_code');
    }
}
