<?php

namespace App\Http\Livewire\Quanlykho;

use App\Http\Livewire\Base\BaseLive;
use App\Models\Gift;
use App\Models\GiftWarehouse;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GiftWarehouseExport;

class Xemkhoquatang extends BaseLive
{
    public $warehouse_id;
    public $giftNameSearch;

    public function mount()
    {
        $this->key_name = 'id';
        $this->sortingName = 'asc';
    }
    public function render()
    {
        $warehouseInfo = GiftWarehouse::where('id', $this->warehouse_id)->first();
        $query = Gift::query();
        $query->where('gift_warehouse_id', $this->warehouse_id);
        if ($this->giftNameSearch) {
            $query->where('gift_name', 'like', '%' . $this->giftNameSearch . '%');
        }

        $data = $query->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage);
        return view('livewire.quanlykho.xemkhoquatang', compact(['data', 'warehouseInfo']));
    }
    public function delete()
    {
        $gift = Gift::findOrFail($this->deleteId);
        $gift->delete();
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Xóa thành công']);
    }
    public function export()
    {
        $gift = Gift::all();
        if ($gift->count() == 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'warning', 'message' => 'Ko có bản ghi nào!']);
            $this->emit('close-modal-export');
        } else {
            return Excel::download(new GiftExport, 'quatang_' . date('Y-m-d-His') . '.xlsx');
        }
    }
}
