<?php

namespace App\Http\Livewire\Ketoan\Baocao;
use App\Http\Livewire\Base\BaseLive;
use App\Models\Customer;
use Maatwebsite\Excel\Facades\Excel;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exports\ListSoQuyExport;

use Livewire\Component;

class ListSoQuy extends BaseLive
{
    public $key_name;
    public $perPage =100;
    public $customerlist = [];
    public $customerid;
    public $fromDate;
    public $toDate;
    public $account_money_id;
    public $account_money_list = [];

    protected $listeners = ['setfromDate', 'settoDate'];

    public function render()
    {
        $data= [];
        $this->dispatchBrowserEvent('setSelect2');
        $this->getAccountMoney();
        $query=$this->GetData();
        $data = $query->paginate($this->perPage)
        ->withPath(route('ketoan.baocao.soquy.index'));

        $dataBegin = $this->getDataBegin()->paginate($this->perPage);
        $dataEnd =  $this->getDataEnd()->paginate($this->perPage);

        return view('livewire.ketoan.baocao.list-so-quy',['data'=>$data ,'data_begin'=>$dataBegin,'data_end'=>$dataEnd]);
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
     public function resetSearch(){

     }
      /**
     *
     */
    public function export(){
        $query = $this->GetData();
        $data = $query->get();
        if ($data->count() == 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'warning', 'message' => 'Ko có bản ghi nào!']);
            $this->emit('close-modal-delete');
        } else {
            $this->emit('close-modal-delete');
            return Excel::download(new ListSoQuyExport($data), 'soquy_' . date('Y-m-d-His') . '.xlsx');
        }
    }

     /**
      *
      */
     public function GetData(){
         //Get begin
         $fromDate = isset($this->fromDate) ? $this->fromDate : Carbon::Today()->format('Y-m-d');
         $toDate = isset($this->toDate) ? $this->toDate : Carbon::Today()->format('Y-m-d');

        $query = DB::table('view_account_money_transactions as v1');
        $query->where('v1.trans_date','>=',$fromDate);
        $query->where('v1.trans_date','<=',$toDate);
        $query->orderBy('v1.account_code', 'asc');
        $query->orderBy('v1.trans_date', 'asc');
        if(isset($this->account_money_id) && $this->account_money_id!="" ){
            $query->where('v1.id','=',$this->account_money_id);
        }

        $query->select('v1.id','v1.account_code','v1.account_number','v1.account_owner','v1.bank_name',
        'v1.trans_date',DB::raw("0 as begin_money")
        ,'v1.in_money','v1.out_money'
        ,DB::raw("0 as end_money")
        ,'v1.note'
        );

        return $query;
     }

     /**
      *
      */
     public function getDataBegin(){
        //Get begin
        $fromDate = isset($this->fromDate) ? $this->fromDate : Carbon::Today()->format('Y-m-d');

        $query = DB::table('view_account_money_transactions_include_begin as v1');
        $query->where('v1.trans_date','<',$fromDate);
        if(isset($this->account_money_id)){
            $query->where('v1.id','=',$this->account_money_id);
        }
        $query->groupBy('v1.id','v1.account_code','v1.account_number','v1.account_owner','v1.bank_name');
        $query->select('v1.id','v1.account_code','v1.account_number','v1.account_owner','v1.bank_name'
        ,DB::raw("sum(in_money-out_money) as begin_money")
        );
        return $query;

     }
     /**
      *
      */
     public function getDataEnd(){
        //Get end
        $toDate = isset($this->toDate) ? $this->toDate : Carbon::Today()->format('Y-m-d');

        $query = DB::table('view_account_money_transactions_include_begin as v1');
        $query->where('v1.trans_date','<=',$toDate);
        if(isset($this->account_money_id)){
            $query->where('v1.id','=',$this->account_money_id);
        }
        $query->groupBy('v1.id','v1.account_code','v1.account_number','v1.account_owner','v1.bank_name');
        $query->select('v1.id','v1.account_code','v1.account_number','v1.account_owner','v1.bank_name'
        ,DB::raw("sum(in_money-out_money) as end_money")
        );
        return $query;
    }

     /**
     *
     */
    public function getAccountMoney(){
        $account_money_list = DB::table('account_money')->whereNull('deleted_at')
        ->select('id','account_code','account_number','account_owner','bank_name')
        ->get();
        $this->account_money_list = $account_money_list;
    }
}
