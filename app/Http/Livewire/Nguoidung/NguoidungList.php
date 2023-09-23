<?php

namespace App\Http\Livewire\Nguoidung;

use App\Http\Livewire\Base\BaseLive;
use App\Models\User;
use App\Models\Role;
use Livewire\Component;

class NguoidungList extends BaseLive
{
    public $searchUserName;
    public $searchEmail;
    public $NameAccount;

    public function render()
    {
        if($this->reset){
            $this->reset=null;
            $this->searchUserName = null;
            $this->searchEmail = null;
            $this->key_name = 'created_at';
            $this->sortingName = 'desc';
            $this->NameAccount = null;
        }
        $this->searchUserName = trim($this->searchUserName);
        $this->searchEmail = trim($this->searchEmail);
        $query = User::with('roles');
        if($this->searchUserName){
            $query->where('users.name','like','%'.$this->searchUserName.'%');
        }
        if($this->searchEmail){
            $query->where('users.email','like','%'.$this->searchEmail.'%');
        }
        if($this->NameAccount){
            $query->where('users.username','like','%'.$this->NameAccount.'%');
        }
        $users = $query->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage);
        $roles = Role::pluck('id');
        foreach ($users as $user) {
            $user->roleDontBelongto = $roles->diff($user->roles->pluck('id'));
        }
        return view('livewire.nguoidung.nguoidung-list', ['data'=>$users]);
    }
    public function delete(){
        if(Auth()->id()==$this->deleteId){
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Không được phép xóa người dùng đang đăng nhập']);
        }
        else {
            $user = User::findOrFail($this->deleteId);
            $user->delete();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Xóa người dùng thành công']);
        }
    }
}
