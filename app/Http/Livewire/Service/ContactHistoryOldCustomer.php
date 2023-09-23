<?php

namespace App\Http\Livewire\Service;

use App\Http\Livewire\Base\BaseLive;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Customer;
use App\Models\ContacMethod;
use App\Models\ContacHistory;

use Illuminate\Database\Eloquent\Builder;

class ContactHistoryOldCustomer extends BaseLive
{
    // Id khách hàng
    public $customerId;
    public $customer;
    public $contactHistories;
    public $contactMethod;
    public $contactDate;
    public $note;

    public function mount()
    {
        $this->contactDate = Carbon::now()->format('Y-m-d');
        $this->loadHistory();
    }
    public function render()
    {
        $contactMethodList = ContacMethod::get()->pluck('method_name', 'id');
        $this->updateUI();
        return view('livewire.service.contact-history-old-customer', ['contactMethodList' => $contactMethodList]);
    }

    public function updateUI()
    {
        $this->dispatchBrowserEvent('setSelect2');
        $this->dispatchBrowserEvent('setContactDatePicker');
    }
    public function loadHistory()
    {
        $this->customer = Customer::with(['contactHistories', 'contactHistories.contactMethod'])->where('id', $this->customerId)->first();
        $this->contactHistories = $this->customer->contactHistories->sortBy('date_contact');
    }
    public function store()
    {
        $this->validate([
            'contactDate' => 'required',
            'contactMethod' => 'required',
            'note' => 'required',
        ], [
            'contactDate.required' => 'Ngày liên hệ bắt buộc',
            'contactMethod.required' => 'Hình thức liên hệ bắt buộc',
            'note.required' => 'Ghi chú bắt buộc',
        ]);
        if ($this->customer) {
            try {
                $contacHistory = new ContacHistory();
                $contacHistory->customer_id = $this->customerId;
                $contacHistory->contact_method_id = $this->contactMethod;
                $contacHistory->date_contact = $this->contactDate;
                $contacHistory->note = $this->note;
                $contacHistory->created_at = Carbon::now();
                $contacHistory->updated_at = Carbon::now();
                $contacHistory->save();
                $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Thêm lịch sử liên hệ thành công']);
                $this->loadHistory();
            } catch (\Throwable $th) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Thêm lịch sử liên hệ thất bại']);
            }
        } else {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Không có thông tin khách hàng']);
        }
    }
}
