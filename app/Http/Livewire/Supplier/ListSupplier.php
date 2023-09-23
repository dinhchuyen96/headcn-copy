<?php

namespace App\Http\Livewire\Supplier;

use App\Exports\SupplierExport;
use App\Http\Livewire\Base\BaseLive;
use App\Models\Supplier;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
class ListSupplier extends BaseLive
{
    public $perPage=10;
    public $deleteId;
    public $supplyCode, $supplyPhone, $email, $supplyUrl, $supplyName, $supplyAddress;


    public function render()
    {
        if($this->reset){
            $this->supplyCode = null;
            $this->supplyPhone = null;
            $this->email = null;
            $this->supplyUrl = null;
            $this->supplyName = null;
            $this->supplyAddress = null;
            $this->reset = false;
        }
        $query= Supplier::leftJoin('ex_province','ex_province.province_code','=','suppliers.province_id')
        ->leftJoin('ex_district','ex_district.district_code','=','suppliers.district_id')
        ->leftJoin('ex_ward','ex_ward.ward_code','=','suppliers.ward_id')

        ->select('suppliers.*',DB::raw('ex_province.name as province_name'),
        DB::raw('ex_district.name as district_name'),
        DB::raw('ex_ward.name as ward_name'));

        $query->where(function ()use ($query){
            if($this->supplyCode){
                $query->where('code','like','%'.$this->supplyCode.'%');
            }
            if($this->supplyPhone){
                $query->orWhere('phone','like','%'.$this->supplyPhone.'%');
            }
            if($this->email){
                $query->orWhere('email','like','%'.$this->email.'%');
            }
            if($this->supplyUrl){
                $query->orWhere('url','like','%'.$this->supplyUrl.'%');
            }
            if($this->supplyName){
                $query->orWhere('suppliers.name','like','%'.$this->supplyName.'%');
            }
            if($this->supplyAddress){
                $query->orWhere('address','like','%'.$this->supplyAddress.'%');
            }
        });
        if($this->key_name){
            $query->orderBy($this->key_name,$this->sortingName);
        }
        $data=$query->paginate($this->perPage);
        return view('livewire.supplier.list-supplier',compact('data'));
    }
    public function delete(){
        $sup=Supplier::findOrFail($this->deleteId);
        $sup->delete();
        $this->emit('close-modal-delete');
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Xóa thành công']);
    }


    public function export(){
        $supply=Supplier::all();
        if ($supply->count() == 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'warning', 'message' => 'Ko có bản ghi nào!']);
            $this->emit('close-modal-export');
        }else{
            return Excel::download(new SupplierExport, 'nhacungcap_'.date('Y-m-d-His').'.xlsx');
        }
    }
}
