<?php

namespace App\Http\Livewire\InstallmentCompany;

use Livewire\Component;
use App\Http\Livewire\Base\BaseLive;
use App\Models\InstallmentCompany;

class InstallmentCompanyEdit extends Component
{
    public $company_name;
    public $company_address;
    public $benefit_percentage;
    public $company_id;

    public function mount($id)
    {

        $data = InstallmentCompany::find($id);
        $this->company_name = $data->company_name;
        $this->company_address = $data->company_address;
        $this->benefit_percentage = $data->benefit_percentage;
        $this->company_id = $id;
//        dd($data);
    }


    public function render()
    {
        $company_name =  $this->company_name;
        $company_address =  $this->company_address;
        $benefit_percentage =  $this->benefit_percentage;
        $id = $this->company_id;
        $this->updateUI();
        return view('livewire.installment-company.installment-company-edit',
            [
                'id' => $id,
                'company_name' => $company_name,
                'company_address' => $company_address,
                'benefit_percentage' => $benefit_percentage,
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
            'company_name' => 'required',
            'company_address' => 'required',
            'benefit_percentage' => 'required | min:0 | max:100'
        ], [
            'name.required' => 'Tên công ty  là bắt buộc',
            'company_address.required' => 'Địa chỉ công ty là bắt buộc',
            'benefit_percentage.required' => 'Phần trăm hoa hồng là bắt buộc',
            'benefit_percentage.min:0' => 'Phần trăm hoa hồng tối thiểu là 0%',
            'benefit_percentage.max:100' => 'Phần trăm hoa hồng tối đa là 100%'

        ]);
        $id = $this->company_id;
        $installmentCompany = InstallmentCompany::where('id', $id)->first();
        $installmentCompany->company_name = $this->company_name;
        $installmentCompany->company_address = $this->company_address;
        $installmentCompany->benefit_percentage = $this->benefit_percentage;
        $installmentCompany->save();

        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Cập nhập thành công']);
    }
}
