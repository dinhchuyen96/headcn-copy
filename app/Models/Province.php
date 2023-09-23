<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    protected $table = 'ex_province';
    protected $fillable = ['province_code', 'created_by', 'created_date', 'last_modified_by', 'last_modified_date', 'coordinate', 'description', 'name', 'short_name', 'status', 'type'];

    public function districts()
    {
        return $this->hasMany(District::class, 'province_code', 'province_code');
    }
}
