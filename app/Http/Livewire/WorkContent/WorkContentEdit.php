<?php

namespace App\Http\Livewire\WorkContent;

use App\Models\InstallmentCompany;
use Livewire\Component;
use App\Models\WorkContent;

class WorkContentEdit extends Component
{
    public $name;
    public $wcid;
    public $type;

    public function mount($id)
    {

        $data = WorkContent::find($id);
        $this->name = $data->name;
        $this->wcid = $id;
        $this->type = $data->type;
    }

    public function render()
    {
        $name = $this->name;
        $wcid = $this->wcid;
        $this->updateUI();
        return view(
            'livewire.workcontent.work-content-edit',
            [
                'wcid' => $wcid,
                'name' => $name,
            ]
        );
    }

    public function updateUI()
    {
        $this->dispatchBrowserEvent('setSelect2');
    }

    public function update()
    {
        $this->validate(
            [
                'name' => 'required',
            ],
            [
                'name.required' => 'Nội dung công việc là bắt buộc',
            ]
        );
        $id = $this->wcid;
        $workContent = WorkContent::where('id', $id)->firstOrFail();
        $workContent->name = $this->name;
        $workContent->save();

        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Cập nhập thành công']);
    }
}
