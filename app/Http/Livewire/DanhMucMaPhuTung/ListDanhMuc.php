<?php

namespace App\Http\Livewire\DanhMucMaPhuTung;

use App\Exports\SupplierExport;
use App\Imports\DanhMucPhuTungImport;

use App\Http\Livewire\Base\BaseLive;
use App\Models\CategoryAccessory;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

use App\Service\Community;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ListDanhMuc extends BaseLive
{
    public $perPage=10;
    public $deleteId;
    public $nameCate;
    public $file;

    public function render()
    {
        if($this->reset){
            $this->nameCate = null;
        }
        $query=  CategoryAccessory::select(['*']);


        $query->where(function ()use ($query){
            if($this->nameCate){
                $query->where('name','like','%'.$this->nameCate.'%');
                $query->orWhere('code','like','%'.$this->nameCate.'%');
            }
        });
        if($this->key_name){
            $query->orderBy($this->key_name,$this->sortingName);
        }
        $dataAC=$query->paginate($this->perPage);
        return view('livewire.danhmucmaphutung.ListCategoryAccessory',compact('dataAC'));
    }
    public function delete(){
        $sup=CategoryAccessory::findOrFail($this->deleteId);
        $sup->delete();
        $this->emit('close-modal-delete');
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Xóa thành công']);
    }


    public function export(){
        // $supply=Supplier::all();
        // if ($supply->count() == 0) {
        //     $this->dispatchBrowserEvent('show-toast', ['type' => 'warning', 'message' => 'Ko có bản ghi nào!']);
        //     $this->emit('close-modal-export');
        // }else{
        //     return Excel::download(new SupplierExport, 'nhacungcap_'.date('Y-m-d-His').'.xlsx');
        // }
    }


    public function import()
    {
        $this->validate([
            'file' => 'required'
        ], [
            'file.required' => 'Hãy chọn file để import',
        ]);
        try {
            DB::beginTransaction();
            Excel::import(new DanhMucPhuTungImport, $this->file);
            $this->emit('close-modal-import');
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Import thành công']);
            DB::commit();
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();
            $ar = [];
            foreach ($failures as $failure) {
                $ar[] = 'Dòng ' . $failure->row() . ': ' . array_values($failure->errors())[0];
            }
            $ar = array_unique($ar);
            foreach ($ar as $item) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $item]);
            }
            return;
        }
    }
    public function downloadExample()
    {
        return Storage::disk('public')->download('mau_file_danh_muc_phu_tung.xlsx');
    }

}
