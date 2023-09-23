<?php

namespace App\Http\Livewire\ServiceList;

use App\Http\Livewire\Base\BaseLive;
use Livewire\Component;
use App\Models\ListService;

class ServiceEdit extends Component
{
    public $serviceType;
    public $serviceName;
    public $listServiceItem;


    public function mount()
    {
        if ($this->listServiceItem) {
            $this->serviceType = $this->listServiceItem->type;
            $this->serviceName = $this->listServiceItem->title;
        }
    }

    public function render()
    {
        $this->updateUI();
        return view('livewire.servicelist.service-list-edit');
    }
    public function updateUI()
    {
        $this->dispatchBrowserEvent('setSelect2');
    }
    public function store()
    {
        $this->validate([
            'serviceType' => 'required',
            'serviceName' => 'required',

        ], [
            'serviceType.required' => 'Loại DV là bắt buộc',
            'serviceName.required' => 'Tên dịch vụ là bắt buộc',
        ]);
        $listService = new ListService();
        $listService->type = $this->serviceType;
        $listService->title = $this->serviceName;
        $listService->save();
        $this->resetInput();
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Cập nhật thành công']);
    }
    public function resetInput()
    {
        $this->serviceType = '';
        $this->serviceName = '';
    }
}
