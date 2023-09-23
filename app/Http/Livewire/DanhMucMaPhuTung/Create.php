<?php

namespace App\Http\Livewire\DanhMucMaPhuTung;

use App\Models\CategoryAccessory;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\PositionInWarehouse;
use Livewire\Component;
use Carbon\Carbon;

class Create extends Component
{
    public $nameCategory ;
    public $checkCategory;

    public $code ;
    public $unit ;
    public $parentcode;
    public $parentname;
    public $parentunit;
    public $changerate;
    public $warehouse_id;
    public $position_in_warehouse_id;

    public $selectwarehouse;     //select warehouse id
    public $itemwarehouseid;
    public $positionWarehouseList = [];
    public $itempositionid;
    public $positionWarehouseId ; //select position warehouse id
    public $isViewMode  =false;

    public $itemparentcode ;
    public $parentcodes=[];

    public $netprice = 0;
    public function mount()
    {
        //get default parentcode
        $parentcodes = CategoryAccessory::where('deleted_at',null)
        ->where('code','<>',$this->code)
        ->orderByDesc('id')
        ->pluck('code');

        if($parentcodes->isNotEmpty()){
            $this->parentcodes = $parentcodes;
        }
        $this->changerate =1;//set default rate

        //TUDN
        $warehouses =Warehouse::all(); // it will get the entire table
        if(!empty($warehouses)){
            $this->warehouses = $warehouses;
            foreach($warehouses as $item){
                $this->selectwarehouse =$item->id;
                //get position warehouse
                break;
            }
        }else $this->selectwarehouse =0;
        //END TUDN
        $m_positionWarehouseList = PositionInWarehouse::where('warehouse_id',$this->selectwarehouse)
                                   ->get();
        if($m_positionWarehouseList){
            $this->positionWarehouseList =$m_positionWarehouseList;
            foreach($m_positionWarehouseList as $item){
                //1. get selected
                $this->positionWarehouseId =$item->id;
                break;
            }
        }else $this->positionWarehouseId = 0;

    }
    public function render()
    {
        $this->dispatchBrowserEvent('setSelect2');
        return view('livewire.danhmucmaphutung.create');
    }



    public function store()
    {
        $this->checkCategory = CategoryAccessory::where('code',trim($this->code))->count() ;
        if($this->checkCategory == 0){
            $this->validate(
                [
                    'code' => 'required|max:255',
                    'nameCategory' => 'required|max:255',
                    'unit' => 'required|max:20',
                    'netprice' =>'integer|gt:0|digits_between:1,9',
                ],
                [
                    'code.required' => 'Bắt buộc nhập mã',
                    'code.max' => 'mã phụ tùng không quá 255 kí tự',
                    'nameCategory.required' => 'Bắt buộc nhập tên danh mục',
                    'nameCategory.max' => 'tên pt không quá 255 kí tự',
                    'unit.required' => 'Đơn vị phải nhập',
                    'unit.max' => 'Đơn vị không quá 20 kí tự',
                    'netprice.integer' => 'giá bán đề xuất phải kiểu số',
                    'netprice.gt' => 'giá bán đề xuất phải lớn hơn 0',
                    'netprice.digits_between' =>'giá bán đề xuất nhỏ hơn 999999999',
                ],
                []
            );

            if($this->parentcode){
                $this->validate(
                    [
                        'changerate' => 'required|integer|gt:0'
                    ],
                    [
                        'changerate.required' => 'Bắt buộc nhập tỉ lệ quy đổi',
                        'changerate.integer' => 'Tỉ lệ quy đổi phải kiểu số',
                        'changerate.integer' => 'Tỉ lệ quy đổi phải lớn hơn 0'
                    ],
                );
            }

            $categoryAccessory = new CategoryAccessory();
            $categoryAccessory->code =  $this->code;
            $categoryAccessory->name =  $this->nameCategory;
            $categoryAccessory->unit =  $this->unit;
            $categoryAccessory->parentcode =  $this->parentcode;
            $categoryAccessory->parentunit =  $this->parentunit;
            $categoryAccessory->changerate =  $this->changerate;
            $categoryAccessory->warehouse_id =  $this->selectwarehouse;
            $categoryAccessory->position_in_warehouse_id =  $this->positionWarehouseId;
            $categoryAccessory->netprice =$this->netprice;

            $categoryAccessory->save();

            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Thêm mới thành công']);
            $this->resetInput();
        }
        else{
            $this->dispatchBrowserEvent('show-toast', ['type' => 'warning', 'message' => 'Mã danh mục phụ tùng đã tồn ']);
        }
    }

    //TUDN
    /**
     * Do event change warehouse
     */
    public function onChangeWarehouse(){
        //render lai vi tri kho
        $warehouse_id = $this->selectwarehouse;
        $m_positionWarehouseList = PositionInWarehouse::where('warehouse_id',$warehouse_id)->get();
        if($m_positionWarehouseList){
            $this->positionWarehouseList = $m_positionWarehouseList;
            foreach($m_positionWarehouseList as $item){
                $this->positionWarehouseId = $item->id;
                break;
            }
        }
    }

    /***
     * when user change parent code
     * get parent unit
     * and it warehouse, position warehouse id
     */
    public function updatedparentcode(){
        $parentcode = $this->parentcode;

        $this->parentcategory = CategoryAccessory::where('parentcode',trim($this->parentcode))->count() ;
        if($this->parentcategory > 0){
            $message = 'Mã phụ tùng cha '. $this->parentcode.' đã được sử dụng';
            $this->dispatchBrowserEvent('show-toast', ['type' => 'warning', 'message' =>$message]);
        }else{
            $partinfo = CategoryAccessory::where('code',$parentcode)->first();
            if($partinfo){
                $this->parentunit = $partinfo->unit;
                $this->selectwarehouse = $partinfo->warehouse_id;
                $this->positionWarehouseId = $partinfo->position_in_warehouse_id ;
                $this->parentname =$partinfo->name;
            }
        }
    }

    /**when user change warehouse
     * get position of select wwarhouse
     */
    public function updatedselectwarehouse(){
        //render lai vi tri kho
        $warehouse_id = $this->selectwarehouse;
        $m_positionWarehouseList = PositionInWarehouse::where('warehouse_id',$warehouse_id)->get();
        if($m_positionWarehouseList){
            $this->positionWarehouseList = $m_positionWarehouseList;
            foreach($m_positionWarehouseList as $item){
                $this->positionWarehouseId = $item->id;
                break;
            }
        }
    }

    /**
     * reset input after
     *
     */
    public function resetInput()
    {
        $this->code = '';
        $this->nameCategory = '';
        $this->unit = '';
        $this->parentcode = '';
        $this->parentunit = '';
        $this->changerate = 1;

          //TUDN
          $warehouses =Warehouse::all(); // it will get the entire table
          if(!empty($warehouses)){
              $this->warehouses = $warehouses;
              foreach($warehouses as $item){
                  $this->selectwarehouse =$item->id;
                  //get position warehouse
                  break;
              }
          }else $this->selectwarehouse =0;
          //END TUDN
          $m_positionWarehouseList = PositionInWarehouse::where('warehouse_id',$this->selectwarehouse)
                                     ->get();
          if($m_positionWarehouseList){
              $this->positionWarehouseList =$m_positionWarehouseList;
              foreach($m_positionWarehouseList as $item){
                  //1. get selected
                  $this->positionWarehouseId =$item->id;
                  break;
              }
          }else $this->positionWarehouseId = 0;

    }
    public function back()
    {
        return redirect()->to('/danhmucmaphutung/danhsach');
    }
}
