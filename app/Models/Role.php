<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\RefreshesPermissionCache;

class Role extends Model
{
    use HasPermissions;
    use RefreshesPermissionCache;
    protected $table = "roles";
    public $autoincrement = true;
    protected $guarded=['*'];
    protected $fillable = [
        'name',
        'guard_name',
    ];
}
