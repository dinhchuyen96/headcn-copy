<?php

namespace App\Http\Livewire\WorkContent;

use App\Http\Livewire\Base\BaseLive;
use App\Models\WorkContent;

class WorkContentList extends BaseLive
{
    public $name;
    public $type;

    public function mount()
    {
        $this->key_name = 'created_at';
        $this->sortingName = 'asc';
    }

    public function render()
    {
        $query = WorkContent::query();

        if ($this->name) {
            $query->where('name', 'like', '%' . $this->name . '%');
        }
        if ($this->type) {
            $query->where('type', 'like', '%' . $this->type . '%');
        }
        $data = $query->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage);
        $this->updateUI();
        return view('livewire.workcontent.work-content-list', ['data' => $data]);
    }
    public function updateUI()
    {
        $this->dispatchBrowserEvent('setSelect2');
    }
}
