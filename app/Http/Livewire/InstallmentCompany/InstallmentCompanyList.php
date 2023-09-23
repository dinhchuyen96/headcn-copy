<?php

namespace App\Http\Livewire\InstallmentCompany;

use Livewire\Component;
use App\Http\Livewire\Base\BaseLive;
use App\Models\InstallmentCompany;

class InstallmentCompanyList extends BaseLive
{
    public $company_name;
    public $company_address;
    public $benefit_percentage;


    public function mount()
    {
        $this->key_name = 'created_at';
        $this->sortingName = 'asc';
    }

    public function render()
    {
        $query = InstallmentCompany::query();
        if ($this->company_name) {
            $query->where('company_name', 'like', '%' . $this->company_name . '%');
        }
        if ($this->company_address) {
            $query->where('company_address', 'like', '%' . $this->company_address . '%');
        }
        if ($this->benefit_percentage) {
            $query->where('benefit_percentage', 'like', '%' . $this->benefit_percentage . '%');
        }
        $data = $query->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage);
        $this->updateUI();
        return view('livewire.installment-company.installment-company-list', ['data' => $data]);
    }

    public function delete($id){
        $company=InstallmentCompany::findOrFail($id);
        $company->delete();
        $this->emit('close-modal-delete');
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Xóa thành công']);
    }

    public function updateUI()
    {
        $this->dispatchBrowserEvent('setSelect2');
    }
}
