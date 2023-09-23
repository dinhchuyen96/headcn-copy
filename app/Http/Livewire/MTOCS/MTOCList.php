<?php

namespace App\Http\Livewire\Mtocs;

use App\Exports\MtocExport;
use App\Http\Livewire\Base\BaseLive;
use App\Models\HMSReceivePlan;
use App\Models\MasterData;
use App\Models\Mtoc;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Imports\MtocImport;
use Illuminate\Support\Facades\DB;

class MtocList extends BaseLive
{
    public $searchById;
    public $searchByColorCode;
    public $searchByColorName;
    public $searchByModelCode;
    public $searchByModelType;
    public $searchByOptionCode;
    public $searchNameColor;
    public $hmsList;
    public $file;

    public function mount()
    {
        //$this->key_name = 'mtoc.created_at';
    }
    public function render()
    {
        if ($this->reset) {
        }
        $query = Mtoc::query()->leftjoin('mto_data', 'mtoc.mtocd', 'mto_data.MTOCD')
            ->select(DB::raw('mtoc.id as id,mtoc.color_name,mtoc.color_code,mtoc.model_code,mtoc.type_code,mtoc.option_code,CONCAT(mtoc.model_code,mtoc.type_code,mtoc.option_code,mtoc.color_code) as mtocCode,mto_data.MODEL_NAME_S as model_name_s,mtoc.created_at as created_at,mtoc.suggest_price as suggest_price'));
        if ($this->searchById) {
            $query->where(DB::raw('CONCAT(mtoc.model_code,mtoc.type_code,mtoc.option_code,mtoc.color_code)'), 'like', '%' . $this->searchById . '%');
        }
        if ($this->searchNameColor) {
            $query->where('color_name', 'like', '%' . $this->searchNameColor . '%');
        }
        if ($this->searchByColorCode) {
            $query->where('mtoc.color_code', 'like', '%' . $this->searchByColorCode . '%');
        }
        if ($this->searchByModelCode) {
            $query->where('mtoc.model_code', 'like', '%' . $this->searchByModelCode . '%');
        }
        if ($this->searchByModelType) {
            $query->where('mtoc.type_code', 'like', '%' . $this->searchByModelType . '%');
        }
        if ($this->searchByOptionCode) {
            $query->where('mtoc.option_code', 'like', '%' . $this->searchByOptionCode . '%');
        }
        $data = $query->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage);
        return view('livewire.mtocs.mtoc-list', ['data' => $data]);
    }
    public function delete()
    {
        $mtoc = Mtoc::findOrFail($this->deleteId);
        $mtoc->delete();
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Xóa thành công']);
    }
    public function export()
    {
        $this->listOrders = Mtoc::query()->get();
        $this->countRecords = $this->listOrders->count();
        if ($this->countRecords == 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Không có bản ghi nào!']);
            $this->emit('close-modal-export');
        } else {
            return Excel::download(new MtocExport, 'danhsachmtoc_' . date('Y-m-d-His') . '.xlsx');
        }
    }
    public function import()
    {

        $this->validate([
            'file' => 'required'
        ], [
            'file.required' => 'Hãy chọn file để import',
        ]);
        try {
            Excel::import(new MtocImport, $this->file);
            if (session()->has('error')) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => session()->get('error')]);
                session()->pull('error');
                return;
            }
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Import thành công']);
            $this->emit('close-modal-import');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $ar = [];
            foreach ($failures as $failure) {
                $ar[] = $failure->errors()[0];
            }
            $ar = array_unique($ar);
            foreach ($ar as $item) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $item]);
            }
        }
    }
    public function downloadExample()
    {
        $this->dispatchBrowserEvent('setSelect2');
        return Storage::disk('public')->download('mau-danh-sach-mtoc.xlsx');
    }
}
