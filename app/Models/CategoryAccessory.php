<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryAccessory  extends Model
{
    use HasFactory; // nó thêm 1 số các hàm để sử dụng vd create ,desginpatent factory
    protected $table ='category_accessories'; // table
    protected $primaryKey = 'id'; // khóa chính
    // col
    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        'code',
        'unit',
        'parentcode',
        'parentunit',
        'warehouse_id',
        'position_in_warehouse_id',
        'changerate',
    ];

    public function accessories()
    {
        return $this->hasMany(Accessory::class);
    }

}
