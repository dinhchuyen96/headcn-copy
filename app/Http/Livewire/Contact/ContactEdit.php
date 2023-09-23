<?php

namespace App\Http\Livewire\Contact;
use App\Http\Livewire\Base\BaseLive;
use App\Models\ContacMethod;
use Livewire\Component;


class ContactEdit extends Component
{
    public $perPage = 10;
    public $deleteId;
    public $method_name;
    public $contact_id;

    public function mount($id)
    {

        $data = ContacMethod::find($id);
        $this->method_name = $data->method_name;
        $this->contact_id = $id;
//        dd($data);
    }

    public function render()
    {
        $company_name =  $this->method_name;

        $id = $this->contact_id;
        $this->updateUI();
        return view('livewire.contact.contact-edit',
            [
                'id' => $id,
                'company_name' => $company_name,

            ]
        );
    }
    public function updateUI()
    {
        $this->dispatchBrowserEvent('setSelect2');
    }
    public function update()
    {
        $this->validate([
            'method_name' => 'required',
        ], [
            'method_name.required' => 'Nội dung công việc là bắt buộc',
        ]);
        $contact = new ContacMethod();
        $contact->method_name = $this->method_name;
        $contact->save();
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Tạo mới thành công']);
    }
}
