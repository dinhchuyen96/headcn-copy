<?php

namespace App\Http\Livewire\ServiceList;

use App\Http\Livewire\Base\BaseLive;
use App\Models\AccountMoney;
use App\Models\ListService;

class ServiceList extends BaseLive
{
    public $serviceType;
    public $serviceName;
    public $deleteId;

    public function mount()
    {
        $this->key_name = 'created_at';
        $this->sortingName = 'asc';
    }
    public function render()
    {
        $query = ListService::query();
        if ($this->serviceType) {
            $query->where('type',  $this->serviceType);
        }
        if ($this->serviceName) {
            $query->where('title', 'like', '%' . $this->serviceName . '%');
        }
        $data = $query->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage);
        $this->updateUI();
        return view('livewire.servicelist.service-list', ['data' => $data]);
    }
    public function delete()
    {
        $listService = ListService::findOrFail($this->deleteId);
        $listService->delete();
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Xóa thành công']);
    }
    public function updateUI()
    {
        $this->dispatchBrowserEvent('setSelect2');
    }
}
