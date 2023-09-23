<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListService extends Model
{
    use HasFactory;
    protected $table = 'list_services';
    protected $fillable = ['title', 'type'];


    public function otherServiceList()
    {
        return $this->hasMany(OtherService::class, 'list_service_id', 'id');
    }
}
