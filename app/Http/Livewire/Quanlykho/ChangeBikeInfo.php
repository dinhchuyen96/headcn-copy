<?php

namespace App\Http\Livewire\Quanlykho;
use App\Http\Livewire\Base\BaseLive;
use App\Models\Warehouse;
use App\Models\Motorbike;
use App\Models\Accessory;
use App\Models\CategoryAccessory;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NavigationMotorbikeExport;
use Log;
use App\Enum\EMotorbike;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Livewire\Component;

class ChangeBikeInfo extends Component
{
    public $Warehouses;
    //Bikeinfo for change bike
    public $bikelist = [] ;
    public $orgbikeframeno ;
    public $orgmodelname;
    public $orgbikeprice;
    public $newprice;
    public $bikenote;
    public $orgpartlist = [] ;
    public $orgpart;
    public $orgpartcode = [] ;
    public $orgpartname = [] ;
    public $orgpartqty = [] ;
    public $orgpartprice = [] ;
    public $orgpartamount = [] ;
    public $orgpartposition = [] ;

    public $newpartlist = [] ;
    public $newpart;
    public $newpartcode = [] ;
    public $newpartname = [] ;
    public $newpartqty = [] ;
    public $newpartprice = [] ;
    public $newpartamount = [] ;
    public $newpartwarehouseid = [] ;
    public $newpartpositionid = [] ;

    protected $listeners = [];


    public function mount(){

    }

    public function render()
    {
        $this->GetBikeList();
        $this->GetOrgPartList();
        $this->GetNewPartList();

        $this->dispatchBrowserEvent('setSelect2');
        return view('livewire.quanlykho.change-bike-info',[  ]);
    }

    /**
     * Org part cua xe
     * la cac part co trong category
     */
    public function GetOrgPartList(){
        $this->orgpartlist  = CategoryAccessory ::whereNull('deleted_at')
        ->select('code','name','netprice')->get();
    }
    /**
     *
     * New part la cac part co trong kho
     * va ton tai ton kho
     */
    public function GetNewPartList(){
        $this->newpartlist  = Accessory ::whereNull('deleted_at')
        ->where('quantity','>',0)
        ->select('code','name','price','warehouse_id','position_in_warehouse_id')
        ->get();
    }

    /**
     * Get bike list
     */
    public function GetBikeList(){
        $this->bikelist = Motorbike::where('is_out', EMotorbike::NOT_OUT)
        ->whereNull('sell_date')
        ->select('chassic_no','engine_no','model_type','color')->get();
    }
    /**Onchange first bike */
    public function Updatedorgbikeframeno(){
        if(isset($this->orgbikeframeno)){
            $bikeinfo =  Motorbike::where('chassic_no',$this->orgbikeframeno)
            ->select('chassic_no','engine_no','model_type','color','price')->first();
            if(isset($bikeinfo)){
                $this->orgmodelname = $bikeinfo->model_type . ' - ' . $bikeinfo->color;
                $this->newprice = $bikeinfo->price ;
            }
        }
    }

    /**
     * handle on change orpartlist
     */
    public function Updatedorgpart(){
        if(isset($this->orgpart)){
            $partInfo  = CategoryAccessory ::where('code',$this->orgpart)
            ->whereNull('deleted_at')
            ->select('code','name','netprice')->first();
            if(isset($partInfo)){
                if(!in_array($partInfo->code,$this->orgpartcode)){
                    array_push($this->orgpartcode,$partInfo->code);
                    array_push($this->orgpartname,$partInfo->name);
                    array_push($this->orgpartqty,1);
                    array_push($this->orgpartprice,$partInfo->netprice);
                    array_push($this->orgpartamount,$partInfo->netprice);

                    //update lai gia cua bike
                    if(isset($this->newprice)){
                        $this->newprice += -1 * (int)$partInfo->netprice;
                    }
                }
            }
        }
        $this->orgpart = '';
    }
     /**
     * handle on change orpartlist
     */
    public function Updatednewpart(){
        if(isset($this->newpart)){
            $partInfo  = Accessory ::where('code',$this->newpart)
            ->whereNull('deleted_at')
            ->where('quantity','>',0)
            ->select('code','name','price','warehouse_id','position_in_warehouse_id')
            ->first();
            if(isset($partInfo)){
                if(!in_array($partInfo->code,$this->newpartcode)){
                    array_push($this->newpartcode,$partInfo->code);
                    array_push($this->newpartname,$partInfo->name);
                    array_push($this->newpartqty,1);
                    array_push($this->newpartprice,$partInfo->price);
                    array_push($this->newpartamount,$partInfo->price);
                    array_push($this->newpartwarehouseid,$partInfo->warehouse_id);
                    array_push($this->newpartpositionid,$partInfo->position_in_warehouse_id);

                    //update lai gia cua bike
                    if(isset($this->newprice)){
                        $this->newprice += (int)$partInfo->price;
                    }
                }
            }
        }
        $this->newpart = '';
    }


    /**
     * remove item from table html
     */
    public function orgpartremoveItem($i)
    {
        //unset($this->inputs[$i]);
        unset($this->orgpartcode[$i]);
        unset($this->orgpartname[$i]);
        unset($this->orgpartqty[$i]);
        unset($this->orgpartprice[$i]);
        //update lai gia cua bike
         if(isset($this->newprice)){
            $this->newprice += (int) $this->orgpartamount[$i];
        }
        unset($this->orgpartamount[$i]);


    }

    /**
     * remove item from table html
     */
    public function newpartremoveItem($i)
    {
        //unset($this->inputs[$i]);
        unset($this->newpartcode[$i]);
        unset($this->newpartname[$i]);
        unset($this->newpartqty[$i]);
        unset($this->newpartprice[$i]);
        //update lai gia cua bike
        if(isset($this->newprice)){
            $this->newprice += -1 * (int)$this->newpartamount[$i];
        }
        unset($this->newpartamount[$i]);
        unset($this->newpartwarehouseid[$i]);
        unset($this->newpartpositionid[$i]);


    }

    /**
     * handle user click nhap lai button
     * Reset all grid and
     */
    public function ResetBikeInfo(){
        $this->orgbikeframeno = '';
        $this->orgmodelname = '';
        $this->newprice = '';
        $this->bikenote = '';
        $this->orgpart = '';
        $this->newpart = '';

         //unset($this->inputs[$i]);
         unset($this->orgpartcode);
         $this->orgpartcode = [];
         unset($this->orgpartname);
         $this->orgpartname = [];
         unset($this->orgpartqty);
         $this->orgpartqty = [];
         unset($this->orgpartprice);
         $this->orgpartprice = [];
         unset($this->orgpartamount);
         $this->orgpartamount = [];

          //unset($this->inputs[$i]);
        unset($this->newpartcode);
        $this->newpartcode =[];
        unset($this->newpartname);
        $this->newpartname =[];
        unset($this->newpartqty);
        $this->newpartqty = [];
        unset($this->newpartprice);
        $this->newpartprice = [];
        unset($this->newpartamount);
         $this->newpartamount = [];
        unset($this->newpartwarehouseid);
        $this->newpartwarehouseid = [];
        unset($this->newpartpositionid);
        $this->newpartpositionid = [];
    }

    /**
     * handle user change org part
     */
    public function Updatedorgpartqty($value,$key){
        if(isset($this->orgpartamount)){
            $this->orgpartamount[$key] = $this->orgpartqty[$key] * $this->orgpartprice[$key];
            if(isset($this->newprice)){
                $this->newprice += -1 * (int)$this->orgpartamount[$key] ;
            }
        }
    }

    /**
     * handle user change new part
     */
    public function Updatednewpartqty($value,$key){
        if(isset($this->newpartamount)){
            $this->newpartamount[$key] = $this->newpartqty[$key] * $this->newpartprice[$key];
            if(isset($this->newprice)){
                $this->newprice += (int)$this->newpartamount[$key] ;
            }
        }
    }

    /**
     * Handle click button doi tt xe
     */
    public function ChangeBikeInfoClick(){
       //1. validate
        $this->ValidateInput();
       //2. Do change
       $this->DoChangeBike();
    }

    /**
     * Validate input
     */
    public function ValidateInput(){
        $this->validate([
            'orgbikeframeno' => 'required',
            'orgmodelname' => 'required',
            'newprice' => 'required|integer|gt:0|digits_between:1,11',
            'bikenote' => 'required',
            'newpartcode' => 'required',
            'orgpartqty.*' => 'required',
            'newpartqty.*' => 'required'
        ], [
            'orgbikeframeno.required' => 'Bạn chưa chọn xe đổi',
            'orgmodelname.required' => 'Bạn chưa nhập model xe',
            'newprice.required' => 'Bạn chưa nhập giá xe',
            'newprice.integer' => 'Giá xe phải kiểu số',
            'newprice.gt' => 'Giá xe phải lớn hơn 0',
            'newprice.digits_between' => 'Giá xe phải nhỏ hơn 999999999',
            'bikenote.required' => 'Nội dung thay đổi bắt buộc nhập',
            'orgpartqty.*.required' => 'Bạn chưa nhập số lượng PT',
            'newpartqty.*.required' => 'Bạn chưa nhập số lượng PT',
            'newpartcode.required' => 'Bạn chưa chọn phụ tùng thay'
        ], []);
    }

    //validate rules
    protected function listInputValidator(){
        $array = [];
        if(isset($this->orgpartcode)){
            foreach ($this->orgpartcode as $key => $value) {
                # code...
                $array['orgpartqty.' . $key] = 'required';
            }
        }
        if(isset($this->newpartcode)){
            foreach ($this->newpartcode as $key => $value) {
                # code...
                $array['newpartcode.' . $key] = 'required';
            }
        }
        return $array;
    }

    /**
     * DO change bi
     * if fail then rollback
     */
    public function DoChangeBike(){
        DB::beginTransaction();
        $message = '';
        try {

            //1. update price cua frame no
            $bikeinfo = Motorbike::where('chassic_no','=',$this->orgbikeframeno)
            ->first();
            if($bikeinfo){
                //1.udate xe gia
                $bikeinfo->price = $this->newprice;
                $bikeinfo->note = isset($this->bikenote) ? $this->bikenote : '';
                $bikeinfo->updated_at = Carbon::now();
                $bikeinfo->save();
                //end 1

                //2. update org part stock
                if (isset($this->orgpartcode)) {
                    # code...
                    foreach ($this->orgpartcode as $key => $value) {
                        # code...
                        $orgpartInfo = CategoryAccessory::where('code',$this->orgpartcode[$key])
                        ->whereNull('deleted_at')
                        ->first();
                        if($orgpartInfo){
                            $partInfo = Accessory::whereNull('deleted_at')
                            ->where('code','=',$this->orgpartcode[$key])
                            ->where('warehouse_id','=',$orgpartInfo->warehouse_id)
                            ->where('position_in_warehouse_id','=',$orgpartInfo->position_in_warehouse_id)
                            ->first();
                            if (isset($partInfo)) {
                                # code...
                                $partInfo->quantity +=(int)$this->orgpartqty[$key];
                                $partInfo->updated_at=Carbon::now();
                                $partInfo->save();
                            }else{
                                $partInfo = new Accessory();
                                $partInfo->code = $this->orgpartcode[$key];
                                $partInfo->quantity =(int)$this->orgpartqty[$key];
                                $partInfo->price =(int)$this->orgpartprice[$key];
                                $partInfo->name = $orgpartInfo->name;
                                $partInfo->warehouse_id =$orgpartInfo->warehouse_id;
                                $partInfo->position_in_warehouse_id=$orgpartInfo->position_in_warehouse_id;
                                $partInfo->buy_date =Carbon::now();
                                $partInfo->created_at=Carbon::now();
                                $partInfo->updated_at=Carbon::now();
                                $partInfo->save();
                            }
                        }
                    }
                }
                //end 2

                //3. reduce new part stock
                if (isset($this->newpartcode)) {
                    foreach ($this->newpartcode as $key => $value) {
                        # code...
                        $partInfo = Accessory::whereNull('deleted_at')
                        ->where('code','=',$this->newpartcode[$key])
                        ->where('warehouse_id','=',$this->newpartwarehouseid[$key])
                        ->where('position_in_warehouse_id','=',$this->newpartpositionid[$key])
                        ->first();
                        if (isset($partInfo)) {
                            if (isset($partInfo)) {
                                # code...
                                $partInfo->quantity += -1 * (int)$this->newpartqty[$key];
                                $partInfo->updated_at=Carbon::now();
                                $partInfo->save();
                            }
                        }
                    }
                }
                //end 3

                DB::commit();
                $message = 'Thay đổi thông tin xe thành công!';
                $this->dispatchBrowserEvent('show-toast', ["type" => "success", "message" => $message]);
            }else{
                DB::rollback();
                $message = 'Xe không tồn tại!';
                $this->dispatchBrowserEvent('show-toast', ["type" => "error", "message" => $message]);
            }
        } catch (\Exception $ex) {
            DB::rollback();
            $message = 'Thay đổi thông tin xe không thành công!';
            $this->dispatchBrowserEvent('show-toast', ["type" => "error", "message" => $message]);
        }finally{
            //DB::endTransaction();
        }
    }
}
