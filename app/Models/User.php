<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes;

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username','name', 'email', 'password', 'email_verified_at','head_name','positions'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];




    /**
     * @param array $params
     * @return array
     */
    public function doLogin(array $params){
        $user = $this::where('username', $params['email'])->first();
        $data = [];
        if (!$user){
            $data['status'] = false;
            $data['message'] = "không tìm thấy user " . $params['email'];
        } elseif ($user->email_verified_at == null){
            $data['status'] = false;
            $data['message'] = "Vui lòng xác nhận email";
        } elseif (auth()->attempt(['username' => $params['email'], 'password' => $params['password']])){
            $data['status'] = true;
            $data['message'] = "Login Success";
        } else{
            $data['status'] = false;
            $data['message'] = "Username or Password is incorrect";
        }
        return $data;
    }

    public function repairTasks ()
    {
        return $this->hasMany(RepairTask::class, 'id_fixer_main');
    }

//
//    public function roles(){
//        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id');

    public function myPermissions()
    {
        return $this->morphToMany(Permission::class, 'model', 'model_has_permissions');

    }

    public function periodicChecklists ()
    {
        return $this->hasMany(Periodic::class, 'service_user_fix_id');
    }

    public function repairBills ()
    {
        return $this->hasMany(RepairBill::class, 'id_fixer_main');
    }
}
