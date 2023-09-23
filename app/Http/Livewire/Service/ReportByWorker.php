<?php

namespace App\Http\Livewire\Service;

use App\Enum\EUserPosition;
use App\Exports\ReportByWorkerExport;
use App\Models\User;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ReportByWorker extends Component
{
    public $perPage = 10;
    public $fromDate;
    public $search;
    public $toDate;
    public $sortingName = "desc";
    public $key_name = "created_at";
    protected $listeners = ['setfromDate', 'settoDate'];

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

        $data = $query->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage)->setPath(route('dichvu.baocaotheotho.index'));
        return view('livewire.service.report-by-worker', compact('data'));
    }

    public function getQuery()
    {
        $fromDate = $this->fromDate;
        $toDate = $this->toDate;
        $query = User::with(['repairTasks' => function ($q) use ($fromDate, $toDate) {
            if ($fromDate) $q->where('created_at', '>=', $fromDate);
            if ($toDate) $q->where('created_at', '<=', $toDate);
        }])->where('positions', EUserPosition::NV_KI_THUAT);
        if ($this->search) {
            $query->where('name', 'like', "%$this->search%");
        }

        return $query;
    }

    public function export()
    {
        return Excel::download(new ReportByWorkerExport(
            $this->getQuery()->orderBy($this->key_name, $this->sortingName)->get(),
            $this->fromDate,
            $this->toDate
        ), 'baocaodoanhthutheotho_' . date('Y-m-d-His') . '.xlsx');
    }
}
