<?php

namespace App\Http\Livewire\Ketoan\Baocao;
use App\Http\Livewire\Base\BaseLive;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\ListService;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ListOutcomeExport;
use App\Enum\ListServiceType;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Livewire\Component;

class ListOutcome extends BaseLive
{
    public $totalmoney = 0 ;
    public $servicetypeid = 0;
    public $listservicetypeid = [] ;

    public $searchfromDate;
    public $searchtoDate;
    public $key_name;
    public $perPage = 10;
    public $content;
    public $incometype;

    public $from_account_money_id ;
    public $to_account_money_id ;
    public $account_money_list = [];
    public $amount;
    public $note;
    public $transfer_date;
    public $transfer_user_id ;
    public $transfer_user_list = [];

    protected $listeners = ['setfromDate', 'settoDate',
                            'settransfer_date','setSelectservicetypeid'];

    public function mount(){
        $this->searchfromDate=reFormatDate(now(),'Y-m-d');
        $this->searchtoDate=reFormatDate(now(),'Y-m-d');
        $this->transfer_date=reFormatDate(now(),'Y-m-d');
        $this->getServiceTypes();
    }

    /**
     * get all chi noi bo type
     * return list service types id
     */
    public function getServiceTypes(){
        $listservicetypeid = [];
        $listservicetypeid = ListService::select('id', 'title')
        ->where('type', ListServiceType::OUT)->get();
        if (isset($listservicetypeid)) {
            # code...
            $this->listservicetypeid = $listservicetypeid ;
        }
    }
    public function render()
    {
        $this->getAccountMoney();
        $this->getTransferUser();

        $this->dispatchBrowserEvent('setSelect2');
        $query=$this->GetIncomes();

        $this->totalmoney = $query->sum('money');

        $data = $query->paginate($this->perPage)->setPath(route('ketoan.baocao.dschi.index'));;
        return view('livewire.ketoan.baocao.list-outcome',['data'=>$data]);
    }


    //start ck noi bo
    /**
     *
     */
    public function getTransferUser(){
        $transfer_user_list = DB::table('users')->whereNull('deleted_at')
        ->select('id','name')
        ->get();
        $this->transfer_user_list = $transfer_user_list;

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

    /**
     *
     */
    public function getTransferType($from_account_money_id,$to_account_money_id){
        $from_account = DB::table('account_money')->where('id','=',$from_account_money_id)
        ->select('type')->first();
        $to_account = DB::table('account_money')->where('id','=',$to_account_money_id)
        ->select('type')->first();
        if(isset($from_account) && isset($to_account)){
            if (strtolower($from_account->type)=='cash' && strtolower($to_account->type)=='bank' ) {
                # code...
                return 100; //nop tien vao tk
            }
            if (strtolower($from_account->type)=='bank' && strtolower($to_account->type)=='cash' ) {
                # code...
                return 101; //rut tien ve quy
            }
            if (strtolower($from_account->type)=='bank' && strtolower($to_account->type)=='bank' ) {
                # code...
                return 102; //chuyen noi bo
            }
        }else{
            return 0;
        }

    }
    public function cknoibo(){
        //0. validate
        $this->ValidateInput();
        DB::beginTransaction();
        $message = '';
        try {
            $type = $this->getTransferType($this->from_account_money_id,$this->to_account_money_id);
            //1. ghi nhan receipt
            $receipt = new Receipt();
            $receipt->money = $this->amount;
            $receipt->user_id = $this->transfer_user_id;
            $receipt->receipt_date = $this->transfer_date;
            $receipt->created_at = now();
            $receipt->updated_at = now();
            $receipt->note = $this->note;
            $receipt->type = $type;
            $receipt->account_money_id = $this->to_account_money_id;
            $receipt->from_account_money_id = $this->from_account_money_id;
            $receipt->save();
            //2. ghi nhan payment
            $payment = new Payment();
            $payment->money = $this->amount;
            $payment->user_id = $this->transfer_user_id;
            $payment->payment_date = $this->transfer_date;
            $payment->created_at = now();
            $payment->updated_at = now();
            $payment->note = $this->note;
            $payment->type = $type;
            $payment->account_money_id = $this->from_account_money_id;
            $payment->to_account_money_id = $this->to_account_money_id;
            $payment->save();
            //3. update balance tk nop -
            $from_account = DB::table('account_money')->where('id','=',$this->from_account_money_id)
            ->select('balance')
            ->first();
            if(isset($from_account)){
                $new_from_account_balance = $from_account->balance - $this->amount;
                DB::table('account_money')->where('id','=',$this->from_account_money_id)
                ->update(['balance'=>$new_from_account_balance]);
            }

            //3. update balance tk nhan +
            $to_account = DB::table('account_money')->where('id','=',$this->to_account_money_id)
            ->select('balance')
            ->first();
            if(isset($to_account)){
                $new_to_account_balance = $to_account->balance + $this->amount;
                DB::table('account_money')->where('id','=',$this->to_account_money_id)
                ->update(['balance'=>$new_to_account_balance]);
            }
            DB::commit();
            $message = 'Chuyển tiền nội bộ thành công!';
            $this->dispatchBrowserEvent('show-toast', ["type" => "success", "message" => $message]);

        } catch (\Exception $ex) {
            DB::rollback();
            $message = 'TChuyển tiền nội bộ không thành công!';
            $this->dispatchBrowserEvent('show-toast', ["type" => "error", "message" => $message]);
        }
    }
    public function ValidateInput()
    {
        //1 require, 2 tk nhan va gui khac nhau , 3 note require , 4 user chuyen require
        $this->validate([
            'note' => 'required',
            'from_account_money_id' => 'required',
            'to_account_money_id' => 'required',
            'amount' => 'required|integer|gt:0|digits_between:1,11',
            'transfer_user_id' => 'required'
        ], [
            'note.required' => 'Bạn chưa ghi nội dung',
            'from_account_money_id.required' => 'Bạn chưa nhập tk chuyển',
            'to_account_money_id.required' => 'Bạn chưa nhập tk nhận',
            'amount.required' => 'Số tiền là bắt buộc',
            'amount.integer' => 'Số tiền là kiểu số',
            'amount.gt' => 'Số tiền lớn hơn 0',
            'amount.digits_between' => 'Giá xe phải nhỏ hơn 999999999',
            'transfer_user_id.required' => 'Bạn chưa chọn người chuyển'
        ], []);

        if($this->from_account_money_id == $this->to_account_money_id){
            $message = 'Tài khoản chuyển và nhận không được trùng nhau';
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error',
             'message' => $message]);
             return ;
        }
    }
    //end ck noi bo

    public function setfromDate($time)
    {
        $this->searchfromDate = date('Y-m-d', strtotime($time['fromDate']));
    }
    public function settoDate($time)
    {
        $this->searchtoDate = date('Y-m-d', strtotime($time['toDate']));
    }
    public function settransfer_date($time)
    {
        $this->transfer_date = date('Y-m-d', strtotime($time['transfer_date']));
    }




    public function sorting($key){

    }

    public function resetSearch(){
        $this->content=  '';
        $this->searchfromDate=reFormatDate(now(),'Y-m-d');
        $this->searchtoDate=reFormatDate(now(),'Y-m-d');
        $this->incometype = [];
        $this->emit('resetDateKendo');
    }


    /**
     *
     */
    public function GetIncomes(){
        $query = DB::table('payments as v1');
        $query->leftJoin('account_money as v2','v1.account_money_id','v2.id');
        $query->leftJoin('account_money as v3','v1.to_account_money_id','v3.id');

        $query->leftJoin('list_services as v4', 'v1.service_id','v4.id');

        if(isset($this->searchfromDate)){
            $query->whereDate('v1.payment_date','>=',$this->searchfromDate);
        }
        if(isset($this->searchtoDate)){
            $query->whereDate('v1.payment_date','<=',$this->searchtoDate);
        }
        if(isset($this->content)){
            $query->where('v1.note','like',$this->content.'%');
        }
        if(isset($this->incometype) && $this->incometype >0 ){
            $query->where('v1.type',$this->incometype);
        }

        //TUDN add get more chi noi bo type
        if(isset($this->servicetypeid) && $this->servicetypeid > 0){
            $query->where('v1.service_id',$this->servicetypeid);
        }
        //end todo

        $query->orderBy('v1.payment_date','DESC');
        $query->select('v1.id','v1.money','v1.payment_date','v1.type',
        'v1.created_at','v1.note',
        'v2.account_code','v2.account_number',
        'v3.account_code as to_account_code','v3.account_number as to_account_number',
        'v1.service_id', 'v4.title'
    );

        return $query;
    }


    public function setSelectservicetypeid($id){
        $this->servicetypeid =$id;
    }


    public function getPaidType($paidType)
    {
        $returval = '';
        switch ($paidType) {
            case 8: // Dịch vụ khác
                $returval= 'Nhập phụ tùng';
                break;
            case 9: // Dịch vụ khác
                $returval= 'Nhập xe';
                break;
            case 10: // Dịch vụ khác
                $returval= 'Chi nội bộ';
                break;
            case 11: // Dịch vụ khác
                $returval= 'Chi phí khác';
                break;
            case 100: //'Nộp tiền ngân hàng'
                $returval= 'Nộp tiền ngân hàng';
                break;
            case 101: //'Rút tiền về quỹ'
                $returval= 'Rút tiền về quỹ';
                break;
            case 102: //'Rút tiền về quỹ'
                $returval= 'Chuyển tiền nội bộ';
                break;
            default:
                # code...
                $returval= '';
                break;
        }
        return $returval;
    }


    public function export()
    {
        $query = $this->GetIncomes();
        $data = $query->get();
        if ($data->count() == 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'warning', 'message' => 'Ko có bản ghi nào!']);
            $this->emit('close-modal-delete');
        } else {
            $this->emit('close-modal-delete');
            return Excel::download(new ListOutcomeExport($data), 'dschi' . date('Y-m-d-His') . '.xlsx');
        }
    }

}
