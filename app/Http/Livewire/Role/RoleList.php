<?php

namespace App\Http\Livewire\Role;

use Livewire\Component;
use App\Http\Livewire\Base\BaseLive;
use App\Models\User;
use App\Models\Role;

class RoleList extends BaseLive
{
    public $searchName;
    public function render()
    {
        if($this->reset){
            $this->reset=null;
            $this->searchName = null;
        }
        $this->searchName = trim($this->searchName); 
        // $this->searchEmail = trim($this->searchEmail); 
        $query = Role::query();
        if($this->searchName){
            $query->where('roles.name','like','%'.$this->searchName.'%');
        }
        $data = $query->orderBy($this->key_name,$this->sortingName)->paginate($this->perPage);
        return view('livewire.role.role-list', ['data'=>$data]);
    }

    public function delete(){
        Role::findOrFail($this->deleteId)->delete();
        $this->dispatchBrowserEvent('show-toast', ["type" => "success", "message" => "Xóa thành công." ]);
    }
}
