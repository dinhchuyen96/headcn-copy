<?php

namespace App\Http\Livewire\Utilities;

use App\Exports\WarningUrgentExport;
use App\Http\Livewire\Base\BaseLive;
use App\Models\HMSPartNotAllowUrgent;
use App\Models\HMSPartOrderPlan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class WarningUrgentList extends BaseLive
{
    public $fromDate;
    public $toDate;
    public $searchCode;
    protected $listeners = ['setfromDate', 'settoDate'];

    public function render()
    {
        $this->searchCode = trim($this->searchCode);
        if ($this->reset) {
            $this->fromDate = null;
            $this->toDate = null;
            $this->searchCode = null;
            $this->emit('resetDateKendo');
        }
        $get_first_day = date('Y-m-01');
        $today = date('Y-m-d');

        $urgents = HMSPartNotAllowUrgent::query()
            ->with('orderPlanDetails.orderPlan')
            ->whereHas('orderPlanDetails', function ($query2) use ($get_first_day, $today) {
                return $query2->whereHas('orderPlan', function ($query3) use ($get_first_day, $today) {
                    return $query3->whereDate('po_date', '>=', $get_first_day)
                        ->whereDate('po_date', '<=', $today)
                        ->where('part_order_type', 'LIKE', '%Urgent Order%');
                });
            });


        if ($this->searchCode) {
            $urgents->whereHas('orderPlanDetails.orderPlan', function (Builder $urgents) {
                $urgents->where('order_number', 'LIKE', '%' . $this->searchCode . '%');
            });
        }
        if ($this->fromDate) {
            $urgents->whereHas('orderPlanDetails.orderPlan', function (Builder $urgents) {
                $urgents->whereDate('po_date', '>=', $this->fromDate);
            });
        }
        if ($this->toDate) {
            $urgents->whereHas('orderPlanDetails.orderPlan', function (Builder $urgents) {
                $urgents->whereDate('po_date', '<=', $this->toDate);
            });
        }
        if ($this->fromDate) {
            $urgents->whereHas('orderPlanDetails.orderPlan', function (Builder $urgents) {
                $urgents->whereDate('po_date', '>=', $this->fromDate);
            });
        }
        if ($this->toDate) {
            $urgents->whereHas('orderPlanDetails.orderPlan', function (Builder $urgents) {
                $urgents->whereDate('po_date', '<=', $this->toDate . ' 23:59:59');
            });
        }
        $data = $urgents->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage);

        return view('livewire.utilities.warning-urgent-list', ['data' => $data]);
    }
    public function export()
    {
        $get_first_day = date('Y-m-01');
        $today = date('Y-m-d');
        $this->listOrders = HMSPartNotAllowUrgent::query()
            ->with('orderPlanDetails.orderPlan')
            ->whereHas('orderPlanDetails', function ($query2) use ($get_first_day, $today) {
                return $query2->whereHas('orderPlan', function ($query3) use ($get_first_day, $today) {
                    return $query3->whereDate('po_date', '>=', $get_first_day)
                        ->whereDate('po_date', '<=', $today)
                        ->where('part_order_type', 'LIKE', '%Urgent Order%');
                });
            })->get();
        $this->countRecords = $this->listOrders->count();
        if ($this->countRecords == 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Không có bản ghi nào!']);
            $this->emit('close-modal-export');
        } else {
            return Excel::download(new WarningUrgentExport, 'danhsachdonhangbaohanhkhan' . date('Y-m-d-His') . '.xlsx');
        }
    }
    public function setfromDate($time)
    {
        $this->fromDate = date('Y-m-d', strtotime($time['fromDate']));
    }
    public function settoDate($time)
    {
        $this->toDate = date('Y-m-d', strtotime($time['toDate']));
    }
}
