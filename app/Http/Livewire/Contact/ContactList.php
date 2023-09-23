<?php

namespace App\Http\Livewire\Contact;

use App\Http\Livewire\Base\BaseLive;
use App\Models\ContacMethod;

class ContactList extends BaseLive
{
    public $method_name;


    public function mount()
    {
        $this->key_name = 'created_at';
        $this->sortingName = 'asc';
    }

    public function render()
    {
        $query = ContacMethod::query();

        if ($this->method_name) {
            $query->where('method_name', 'like', '%' . $this->method_name . '%');
        }
        $data = $query->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage);
        $this->updateUI();
        return view('livewire.contact.contact-list', ['data' => $data]);
    }
    public function updateUI()
    {
        $this->dispatchBrowserEvent('setSelect2');
    }
    public function delete($id){
        $company=ContacMethod::findOrFail($id);
        $company->delete();
        $this->emit('ModalDelete');
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Xóa thành công']);
    }
}
