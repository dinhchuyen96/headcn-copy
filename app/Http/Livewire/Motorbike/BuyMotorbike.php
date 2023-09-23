<?php

namespace App\Http\Livewire\Motorbike;

use App\Http\Livewire\Base\BaseLive;
use Livewire\Component;
use App\Models\Motorbike;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\District;
use App\Models\Province;
use App\Models\Ward;
use App\Models\MasterData;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Mtoc;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Storage;
use App\Imports\BuyMotorbikeImport;
use App\Imports\CNBHImport;
use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Enum\EMotorbike;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use DateTime;


class BuyMotorbike extends BaseLive
{
    public $supplierCode;
    public $address;
    public $name;
    public $phone;
    public $email;
    public $district_id;
    public $province_id;
    public $ward_id;
    public $autoFill = false;
    public $warehouse;
    public $isViewMode;
    public $isEditMode = false;
    public $isHVN = false;
    public $order_id;
    public $addBtn = true;
    public $file;
    public $fileCNBH;
    public $barCode;
    public $check_hvn_plan = true;
    protected $listeners = ['setBtnAddStatus', 'setAddress', 'addBarCode'];

    public function mount()
    {
        if (isset($_GET['show'])) {
            $this->isViewMode = true;
        }
        if (isset($_GET['id'])) {
            $this->isEditMode = true;
            $this->order_id = $_GET['id'];
            $supply_id = Order::find($_GET['id'])->supplier_id;
            if ($supply_id) {
                $supply = Supplier::find($supply_id);
                $this->supplierCode = $supply->code;
                $this->name = $supply->name;
                $this->address = $supply->address;
                $this->phone = $supply->phone;
                $this->email = $supply->email;
                $this->ward_id = $supply->ward_id;
                $this->district_id = $supply->district_id;
                $this->province_id = $supply->province_id;
            }
            $this->warehouse = Order::find($_GET['id'])->warehouse_id;
        }
    }

    public function render()
    {
        $warehouseList = Warehouse::get()->pluck('name', 'id')->sortBy('name');
        $this->updateUI();
        return view('livewire.motorbike.buy-motorbike', compact('warehouseList'));
    }

    public function updatedSupplierCode()
    {
        $this->supplierCode = trim($this->supplierCode);
        $this->emit('updateCode', $this->supplierCode);
        if ($this->supplierCode == 'HVN') {
            if ($this->order_id) {
                $supplier_id = Order::findOrFail($this->order_id)->supplier_id;
                if ($supplier_id) {
                    if (Supplier::find($supplier_id)->code != 'HVN') {
                        $this->validateOnly('supplierCode', [
                            'supplierCode' => 'not_in:HVN'
                        ], [
                            'supplierCode.not_in' => 'Không được sửa đơn nhập đã tạo sang nhà CC HVN',
                        ]);
                    }
                }
            }
            OrderDetail::where('category', EOrderDetail::CATE_MOTORBIKE)->where('type', EOrderDetail::TYPE_NHAP)->where('status', EOrderDetail::STATUS_SAVE_DRAFT)->delete();
            // $this->emit('loadListInput');
        }
        $this->emit('checkSupplier', $this->supplierCode);
        $supplier = Supplier::where('code', $this->supplierCode)->get()->first();
        if ($supplier) {
            $this->autoFill = true;
            $this->address = $supplier->address;
            $this->name = $supplier->name;
            $this->phone = $supplier->phone;
            $this->email = $supplier->email;
            $this->district_id = $supplier->district_id;
            $this->province_id = $supplier->province_id;
            $this->ward_id = $supplier->ward_id;
            if ($this->province_id || $this->district_id || $this->ward_id || $this->address) {
                $this->emit('fillAddress', $this->province_id, $this->district_id, $this->ward_id, $this->address);
            }
        } else {
            $this->name = '';
            $this->address = '';
            $this->phone = '';
            $this->email = '';
            $this->province_id = '';
            $this->district_id = '';
            $this->ward_id = '';
            $this->emit('resetAddress');
        }
    }

    public function resetData()
    {
        $this->name = '';
        $this->address = '';
        $this->phone = '';
        $this->email = '';
        $this->province_id = '';
        $this->district_id = '';
        $this->ward_id = '';
        $this->warehouse = '';
        $this->barCode = '';
        $this->emit('resetAddress');
    }

    public function add()
    {
        $this->addBtn = false;
        $this->emit('addNew');
    }

    public function setBtnAddStatus()
    {
        $this->addBtn = true;
    }

    public function store()
    {
        $detail = OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)->where('type', EOrderDetail::TYPE_NHAP)->where('category', EOrderDetail::CATE_MOTORBIKE)->where('admin_id', auth()->id())->get()->toArray();
        $this->validate([
            'supplierCode' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'warehouse' => 'required',
        ], [], [
            'supplierCode' => 'Mã nhà CC',
            'phone' => 'Số điện thoại',
            'name' => 'Nhà cung cấp',
            'warehouse' => 'Kho',
        ]);

        if (!$detail && !$this->order_id) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Chưa có xe máy nào được nhập']);
            return;
        } elseif (!$this->addBtn) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Có bản nháp chưa hoàn thành']);
            return;
        }
        $supplier = Supplier::updateOrCreate([
            'code' => $this->supplierCode,
        ], [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'province_id' => $this->province_id,
            'district_id' => $this->district_id,
            'ward_id' => $this->ward_id,
        ]);
        if ($this->order_id) {
            $order = Order::findOrFail($this->order_id);
        } else {
            $order = new Order();
        }
        $order->created_by = auth()->id();
        $order->category = EOrder::CATE_MOTORBIKE;
        $order->order_type = EOrder::ORDER_TYPE_BUY;
        $order->type = EOrder::TYPE_NHAP;
        $order->supplier_id = $supplier->id;
        $order->warehouse_id = $this->warehouse;
        $order->status = EOrder::STATUS_UNPAID;
        $order->save();

        $order_detail = OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)
            ->where('type', EOrderDetail::TYPE_NHAP)
            ->where('category', EOrderDetail::CATE_MOTORBIKE)
            ->where('admin_id', auth()->id())->get();

        OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)
            ->where('type', EOrderDetail::TYPE_NHAP)
            ->where('category', EOrderDetail::CATE_MOTORBIKE)
            ->where('admin_id', auth()->id())->update([
                'status' => EOrderDetail::STATUS_SAVED,
                'order_id' => $order->id,
            ]);
        Motorbike::where('order_id', $order->id)
            ->where('is_out', EMotorbike::NOT_OUT)
            ->update([
                'supplier_id' => $supplier->id,
            ]);

        foreach ($order_detail as $item) {

            $motorbike =  new Motorbike();
            $motorbike->price = $item->price;
            $motorbike->chassic_no = $item->chassic_no;
            $motorbike->engine_no = $item->engine_no;
            $motorbike->model_code = $item->model_code;
            $motorbike->model_type = $item->model_type;
            $motorbike->model_list = $item->model_list;
            $motorbike->color = $item->color;
            $motorbike->supplier_id = $supplier->id;
            $motorbike->order_id = $order->id;
            $motorbike->buy_date = $item->buy_date;
            $motorbike->status = EMotorbike::NEW_INPUT;
            $motorbike->mtoc_id = null;
            $motorbike->warehouse_id =  $this->warehouse;
            $motorbike->save();
            OrderDetail::where('chassic_no', $item->chassic_no)->update(['product_id' => $motorbike->id]);
        }

        $order->update([
            'total_items' => $order->details->count(),
            'order_no' => 'ORDER_' . $order->id,
            'total_money' => OrderDetail::where('order_id',  $order->id)->sum('price'),
        ]);

        if ($this->order_id) {
            $this->emit('loadListInput');
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Cập nhật thành công']);
        } else {
            $this->emit('loadListInput');
            $this->resetData();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Tạo mới thành công']);
            $this->supplierCode = '';
        }


        // $this->emit('storeOrder', $this->warehouse, $supplier->id);
    }

    public function import()
    {

        $this->validate([
            'file' => 'required'
        ], [
            'file.required' => 'Hãy chọn file để import',
        ]);
        try {
            DB::beginTransaction();
            Excel::import(new BuyMotorbikeImport($this->check_hvn_plan), $this->file);
            if (session()->has('error')) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => session()->get('error')]);
                session()->pull('error');
                return;
            }
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Import thành công']);
            $this->emit('close-modal-import');
            DB::commit();
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();
            $ar = [];
            foreach ($failures as $failure) {
                $ar[] = $failure->errors()[0];
            }
            $ar = array_unique($ar);
            foreach ($ar as $item) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $item]);
            }
            return;
        }
    }

    public function importCNBH()
    {

        $this->validate([
            'fileCNBH' => 'required'
        ], [
            'fileCNBH.required' => 'Hãy chọn file để import',
        ]);
        try {
            DB::beginTransaction();
            $collection = Excel::toCollection(new CNBHImport, $this->fileCNBH, null);
            $sheetData = $collection[0];
            $countImported = 0;
            $warehouse = Warehouse::firstOrCreate(
                [
                    'name' => 'Kho cũ'
                ],
                [
                    'address' => 'Kho cũ',
                    'established_date' => Carbon::now()
                ]
            );
            $dataImport = [];
            foreach ($sheetData as $key => $row) {
                if(gettype($row[21]) == 'string') {
                    $sellDate = ($row[21]);
                    $sellDate = DateTime::createFromFormat('d/m/Y', $sellDate);
                    $sellDate = $sellDate->format('Y-m-d');
                } else {
                    $sellDate = intval($row[21]);
                    $sellDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($sellDate)->format('Y-m-d') ;
                }
                $warehouseId = $warehouse->id;
                $adminId = auth()->id();
                $phone = str_replace(' ', '',(empty($row[14]) ? ((str_starts_with($row[13], '0') ? '' : 0) . $row[13]) : (str_starts_with($row[14], '0') ? '' : 0) . $row[14]));
                $customer = Customer::where('phone', $phone)
                ->first();
                $model = [
                    'chassic_no' => $row[1],
                    'engine_no' => $row[2],
                    'model_code' => $row[5],
                    'color' => $row[12],
                    'quantity' => 1,
                    'price' => null,
                    'supplier_id' => null,
                    'customer_id' => null,
                    'status' => EMotorbike::SOLD,
                    'buy_date' => null,
                    'warehouse_id' => $warehouseId,
                    'admin_id' => $adminId,
                    'sell_date' => $sellDate,
                    'order_id' => null,
                    'model_list' => null,
                    'model_type' => null,
                    'head_sell' => null,
                    'motor_numbers' => $row[20],
                    'head_get' => null,
                    'is_out' => EMotorbike::NOT_OUT,
                    'customer_id' => $customer->id,
                    'customer_phone' => $phone,
                ];
                Motorbike::updateOrCreate([
                    'chassic_no' => $row[1],
                    'engine_no' => $row[2],
                ], $model);
                // $dataImport[] = $model;
                $countImported++;
            }
            // foreach (array_chunk($dataImport, 1000) as $data) {
            // Motorbike::upsert(
            //     $dataImport,
            //     ['chassic_no', 'engine_no'],
            //     ['model_code', 'color', 'quantity', 'price', 'supplier_id', 'customer_id', 'status', 'buy_date', 'warehouse_id', 'admin_id', 'sell_date', 'order_id', 'model_list', 'model_type', 'head_sell', 'motor_numbers', 'head_get', 'is_out', 'customer_phone']
            // );
            // }

            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => "Dữ liệu CNBH (" . $countImported . " item) import thành công"]);
            $this->emit('close-modal-import-cnbh');
            DB::commit();
        } catch (\Throwable $th) {
            dd($th);
            DB::rollBack();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => "Dữ liệu CNBH import thất bại"]);
            return;
        }
    }
    public function downloadExample()
    {
        return Storage::disk('public')->download('mau_file_nhap_xe_may.xlsx');
    }
    
    public function downloadExampleCNBH_Xe()
    {
        return Storage::disk('public')->download('template_CNBH_xe.xlsx');
    }
    public function addBarCode($code)
    {
        $this->validate([
            'barCode' => 'required'
        ], [
            'barCode.required' => 'Barcode bắt buộc phải nhập'
        ], []);
        $this->barCode = $code;
        $this->emit('addInputRow', $code);
    }
    public function UpdateUI()
    {
        $this->dispatchBrowserEvent('setSelect2');
    }
}
