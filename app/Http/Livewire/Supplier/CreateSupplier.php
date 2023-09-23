<?php

namespace App\Http\Livewire\Supplier;

use App\Models\District;
use App\Models\Province;
use App\Models\Supplier;
use App\Models\Ward;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateSupplier extends Component
{
    public $supplyCode, $phoneNumber, $email, $supplyUrl, $supplyName, $address;
    public $supplyWard, $supplyDistrict, $supplyProvince;
    public function render()
    {
        $ward = [];
        $district = [];
        $province = Province::orderBy('name')->pluck('name', 'province_code');
        if ($this->supplyProvince) {
            $district = District::where('province_code', $this->supplyProvince)->orderBy('name')->pluck('name', 'district_code');
            if ($this->supplyDistrict) {
                $ward = Ward::where('district_code', $this->supplyDistrict)->orderBy('name')->pluck('name', 'ward_code');
            }
            $this->dispatchBrowserEvent('setSelect2');
        }

        return view('livewire.supplier.create-supplier', compact('province', 'district', 'ward'));
    }
    public function store()
    {
        $getCode = DB::table('suppliers')->where('deleted_at', '=', null);
        $isCheck = $getCode->where('code', '=', $this->supplyCode)->count();
        if ($isCheck == 0) {
            $this->validate([
                'supplyCode' => 'required',
                'supplyName' => 'required',
                'phoneNumber' => 'required',
            ]);
        } else {
            $this->validate([
                'supplyCode' => 'required|unique:suppliers,code',
                'supplyName' => 'required',
                'phoneNumber' => 'required',
            ]);
        }



        $supply = new Supplier();
        $supply->code = $this->supplyCode;
        $supply->phone = $this->phoneNumber;
        $supply->email = $this->email;
        $supply->url = $this->supplyUrl;
        $supply->name = $this->supplyName;
        $supply->address = $this->address;
        $supply->province_id = $this->supplyProvince;
        $supply->district_id = $this->supplyDistrict;
        $supply->ward_id = $this->supplyWard;
        $supply->save();
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Thêm mới thành công']);
        $this->resetInput();
    }
    public function resetInput()
    {
        $this->supplyCode = '';
        $this->phoneNumber = '';
        $this->email = '';
        $this->supplyUrl = '';
        $this->supplyName = '';
        $this->address = '';
        $this->supplyProvince = '';
        $this->supplyWard = '';
    }
    public function back()
    {
        return redirect()->to('/nhacungcap/ds-nhacungcap');
    }
}
