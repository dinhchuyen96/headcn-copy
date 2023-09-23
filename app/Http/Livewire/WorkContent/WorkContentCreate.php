<?php

namespace App\Http\Livewire\WorkContent;

use App\Http\Livewire\Base\BaseLive;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\WorkContent;

class WorkContentCreate extends Component
{
    public $name;
    public $typeList = [];
    public $type;

    public function mount()
    {
    }

    public function render()
    {
        $this->typeList = [
            1 => "Công việc ngoài",
            0 => "Công việc trong"
        ];
        $this->updateUI();
        return view('livewire.workcontent.work-content-create', ['typeList' => $this->typeList]);
    }
    public function updateUI()
    {
        $this->dispatchBrowserEvent('setSelect2');
    }
    public function store()
    {
        $this->validate([
            'name' => 'required',
            'type' => 'numeric|min:0|max:1',
        ], [
            'name.required' => 'Nội dung công việc là bắt buộc',
            'type.numeric' => 'Loại công việc là bắt buộc',
            'type.min' => 'Loại công việc nằm trong khoảng 0 - 1',
            'type.max' => 'Loại công việc nằm trong khoảng 0 - 1',
        ]);
        $workContent = new WorkContent();
        $workContent->name = $this->name;
        $workContent->type = $this->type;
        $workContent->save();
        $this->resetInput();
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Tạo mới thành công']);
    }
    public function resetInput()
    {
        $this->name = '';
        $this->type = '';
    }
}
