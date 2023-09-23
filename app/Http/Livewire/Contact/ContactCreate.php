<?php

namespace App\Http\Livewire\Contact;

use App\Http\Livewire\Base\BaseLive;
use Livewire\Component;
use App\Models\ContacMethod;

class ContactCreate extends BaseLive
{
    public $method_name;

    public function mount()
    {
    }

    public function render()
    {
        $this->updateUI();
        return view('livewire.contact.contact-create');
    }
    public function updateUI()
    {
        $this->dispatchBrowserEvent('setSelect2');
    }
    public function store()
    {
        $this->validate([
            'method_name' => 'required',
        ], [
            'method_name.required' => 'Nội dung công việc là bắt buộc',
        ]);
        $contact = new ContacMethod();
        $contact->method_name = $this->method_name;
        $contact->save();
        $this->resetInput();
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Tạo mới thành công']);
    }
    public function resetInput()
    {
        $this->method_name = '';
    }
}
