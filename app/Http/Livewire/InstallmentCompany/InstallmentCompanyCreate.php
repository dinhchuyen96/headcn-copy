<?php

namespace App\Http\Livewire\InstallmentCompany;

use App\Models\InstallmentCompany;
use Livewire\Component;

class InstallmentCompanyCreate extends Component
{
    public $company_name;
    public $company_address;
    public $benefit_percentage;

    public function mount()
    {
    }

    public function render()
    {
        return view('livewire.installment-company.installment-company-create');
    }

    public function store()
    {
        $this->validate([
            'company_name' => 'required',
            'company_address' => 'required',
            'benefit_percentage' => 'required | min:0 | max:100'
        ], [
            'company_name.required' => 'Tên công ty  là bắt buộc',
            'company_address.required' => 'Địa chỉ công ty là bắt buộc',
            'benefit_percentage.required' => 'Phần trăm hoa hồng là bắt buộc',
            'benefit_percentage.min:0' => 'Phần trăm hoa hồng tối thiểu là 0%',
            'benefit_percentage.max:100' => 'Phần trăm hoa hồng tối đa là 100%'

        ]);
        $installmentCompany = new InstallmentCompany();
        $installmentCompany->company_name = $this->company_name;
        $installmentCompany->company_address = $this->company_address;
        $installmentCompany->benefit_percentage = $this->benefit_percentage;
        $installmentCompany->save();
        $this->resetInput();
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Tạo mới thành công']);
    }
    public function resetInput()
    {
        $this->company_name = '';
        $this->company_address = '';
        $this->benefit_percentage = '';
    }
}
