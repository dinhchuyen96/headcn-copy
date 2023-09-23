<?php

namespace App\Http\Livewire\Service;

use App\Enum\EUserPosition;
use App\Exports\ReportByWorkExport;
use App\Http\Livewire\Base\BaseLive;
use App\Models\WorkContent;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ReportByWork extends BaseLive
{
    public $perPage = 10;
    public $fromDate;
    public $search;
    public $toDate;
    public $sortingName = "desc";
    public $key_name = "created_at";
    protected $listeners = ['setfromDate', 'settoDate'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->fromDate = date('Y-m-01');
        $this->toDate = date('Y-m-d');
    }

    public function setfromDate($time)
    {
        $this->fromDate = date('Y-m-d', strtotime($time['fromDate']));
    }

    public function settoDate($time)
    {
        $this->toDate = date('Y-m-d', strtotime($time['toDate']));
    }

    public function render()
    {
        $query = $this->getQuery();

        $data = $query->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage);
        $search = $this->search;
        $fromDate = $this->fromDate;
        $toDate = $this->toDate;
        return view('livewire.service.report-by-work', compact('data', 'search', 'fromDate', 'toDate'));
    }

    public function getQuery()
    {
        $fromDate = $this->fromDate;
        $toDate = $this->toDate;
        $query = WorkContent::with(['repairTasks' => function ($q) use ($fromDate, $toDate) {
            if ($fromDate) $q->where('created_at', '>=', $fromDate . ' 00:00:00');
            if ($toDate) $q->where('created_at', '<=', $toDate . ' 23:59:59');
        }])->whereHas('repairTasks.user', function ($q) {
            $q->where('positions', EUserPosition::NV_KI_THUAT);
        });
        if ($this->search) {
            $query->where('name', 'like', "%$this->search%");
        }

        return $query;
    }

    public function export()
    {
        return Excel::download(new ReportByWorkExport(
            $this->getQuery()->orderBy($this->key_name, $this->sortingName)->get(),
            $this->fromDate,
            $this->toDate
        ), 'baocaodoanhthutheocongviec_' . date('Y-m-d-His') . '.xlsx');
    }
}
