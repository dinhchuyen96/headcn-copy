<?php

namespace App\Http\Livewire\Service;

use App\Http\Livewire\Base\BaseLive;
use App\Models\Customer;
use Maatwebsite\Excel\Facades\Excel;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exports\ListContactCustomerExport;
use Livewire\Component;

class ListContactCustomer extends BaseLive
{
    public $key_name;
    public $perPage =10;
    public $customerlist = [];
    public $customerid;
    public $fromDate;
    public $toDate;
    public $customernameorphone;
    public $contactstatus;
    protected $listeners = ['setfromDate', 'settoDate'];


    public function render()
    {
        try {
            //code...
            $this->getCustomerList();
            $this->dispatchBrowserEvent('setSelect2');

            $query = $this->getCustomerContacts();
            $data = $query->paginate($this->perPage)->setPath(route('cskh.ds-lien-he-khach-hang.index'));
            return view('livewire.service.list-contact-customer',['data'=>$data]);
        } catch (Exception $e) {
            //throw $th;
            Log::info($e);
        }
    }


    /**
     *
     */
    public function getCustomerList(){
        $customerlist = Customer::whereNull('deleted_at')
        ->orderBy('name','ASC')
        ->select('id','code','name','phone')
        ->get();
        if(isset($customerlist)){
            $this->customerlist = $customerlist;
        }
    }


    public function getCustomerContacts(){
        $query = DB::table('view_customer_revenue as v1');
        $query->leftJoin('contact_history as v4', function ($join) {
            $join->on('v1.id','=','v4.customer_id');
            $join->on('v1.periodic_level','=','v4.lan_ktdk');
        });
        $query->leftJoin('contact_method as v5', function ($join){
            $join->on('v4.contact_method_id','=','v5.id');
        });

        $query->whereNull('v5.deleted_at');

        if(isset($this->fromDate)){
            $query->where('v1.sell_date','>=',$this->fromDate);
        }
        if(isset($this->toDate)){
            $query->where('v1.sell_date','<=',$this->toDate);
        }
        if(isset($this->contactstatus) && $this->contactstatus ==1){
            $query->whereNotNull('v4.contact_method_id');
        }
         if(isset($this->contactstatus) && $this->contactstatus ==0){
            $query->whereNull('v4.contact_method_id');
        }



        if(isset($this->customernameorphone)){
            $query->where('v1.code','like',$this->customernameorphone. '%');
            $query->orWhere('v1.name','like',$this->customernameorphone. '%');
            $query->orWhere('v1.phone','like',$this->customernameorphone. '%');
        }

        $query->orderBy('v1.name','ASC');
        $query->orderBy('v1.sell_date','ASC');
        $query->select('v1.id','v1.code','v1.name','v1.phone','v1.address',
        'v1.sell_date','v1.periodic_level',
        DB::raw('v1.total_amount as total_revenue'),
        'v4.contact_method_id','v5.method_name'
        );
        //$query->groupBy('v1.id','v1.code','v1.name','v1.phone','v1.address');

        return $query;
    }

    //end ck noi bo

    public function setfromDate($time)
    {
         $this->fromDate = date('Y-m-d', strtotime($time['fromDate']));
    }
    public function settoDate($time)
    {
         $this->toDate = date('Y-m-d', strtotime($time['toDate']));
    }

    /**
     *
     */
    public function sorting($arg){

    }
    /**
     *
     */
    public function export(){
        $query = $this->getCustomerContacts();
        $data = $query->get();
        if ($data->count() == 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'warning', 'message' => 'Ko có bản ghi nào!']);
            $this->emit('close-modal-delete');
        } else {
            $this->emit('close-modal-delete');
            return Excel::download(new ListContactCustomerExport($data), 'dslienhe' . date('Y-m-d-His') . '.xlsx');
        }
    }
}
