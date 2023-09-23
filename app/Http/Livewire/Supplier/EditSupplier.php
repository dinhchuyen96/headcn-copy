<?php

namespace App\Http\Livewire\Supplier;

use App\Http\Livewire\Base\BaseLive;
use App\Models\District;
use App\Models\Province;
use App\Models\Supplier;
use App\Models\Ward;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class EditSupplier extends BaseLive
{
    public $supply_id;
    public $supplyWard, $supplyDistrict,$supplyProvince;
    public $supplyCode, $phoneNumber, $email, $supplyUrl, $supplyName, $address;
    public function mount(){
        $supply= Supplier::findOrFail($this->supply_id);
        $this->supplyCode=$supply->code;
        $this->phoneNumber =$supply->phone;
        $this->email=$supply->email;
        $this->supplyUrl=$supply->url;
        $this->supplyName=$supply->name;
        $this->address=$supply->address;
        $this->supplyProvince=$supply->province_id;
        $this->supplyDistrict=$supply->district_id;
        $this->supplyWard=$supply->ward_id;
    }
    public function render()
    {
        $ward=[];
        $district=[];
        $province=Province::orderBy('name')->pluck('name','province_code');
        if($this->supplyProvince){
            $district=District::where('province_code',$this->supplyProvince)->orderBy('name')->pluck('name','district_code');
            if($this->supplyDistrict){
                $ward=Ward::where('district_code',$this->supplyDistrict) ->orderBy('name')->pluck('name','ward_code');
            }
        }
        $this->dispatchBrowserEvent('setSelect2');
        return view('livewire.supplier.edit-supplier',compact('ward','district','province'));
    }
    public function update(){
        $getCode = DB::table('suppliers')->where('deleted_at','=',null);
        $isCheck = $getCode->where('code','=',$this->supplyCode)->count();
        $getCode->where('id', '=', $this->supply_id);
        $codeSupplier= $getCode->where('code','=',$this->supplyCode)->count();

        if($isCheck==0){
            $this->validate([
                'supplyCode' => 'required',
                'supplyName' => 'required',
                'phoneNumber' => 'required',
            ]);
        } else if($codeSupplier == $isCheck){
            $this->validate([
                'supplyCode' => 'required',
                'supplyName' => 'required',
                'phoneNumber' => 'required',
            ]);
        }
        else{
            $this->validate([
                'supplyCode' => 'required|unique:suppliers,code',
                'supplyName' => 'required',
                'phoneNumber' => 'required',
            ]);
        }
        $supply= Supplier::findOrFail($this->supply_id);
        $supply->code=$this->supplyCode;
        $supply->phone=$this->phoneNumber;
        $supply->email=$this->email;
        $supply->url=$this->supplyUrl;
        $supply->name=$this->supplyName;
        $supply->address=$this->address;
        $supply->province_id=$this->supplyProvince;
        $supply->district_id=$this->supplyDistrict;
        $supply->ward_id=$this->supplyWard;
        $supply->save();
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Cập nhật thành công']);
    }
    public function back(){
        return redirect()->to('/nhacungcap/ds-nhacungcap');
    }
}
