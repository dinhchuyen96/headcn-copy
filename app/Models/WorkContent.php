<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkContent extends Model
{
    use HasFactory;
    protected $table = 'work_content';

    public function repairTasks ()
    {
        return $this->hasMany(RepairTask::class, 'work_content_id');
    }
}
