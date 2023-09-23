<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;
    protected $table='ex_ward';
    protected $fillable=['ward_code','created_by','created_date','last_modified_by','last_modified_date','description','district_code','name','short_name','status','type'];

    public function district() {
        return $this->belongsTo(District::class, 'district_code', 'district_code');
    }
}
