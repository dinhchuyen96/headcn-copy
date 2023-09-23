<?php

namespace App\Http\Livewire\Motorbike;

use App\Http\Livewire\Base\BaseLive;
use Livewire\Component;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use App\Component\Recursive;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ListOrderBuyMotorbikeExport;
use App\Models\Motorbike;
use App\Exports\MotorbikeExport;
use App\Models\Supplier;
use App\Models\OrderDetail;
use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Models\Mtoc;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Writer as Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Enum\EPaymentMethod;

class ListOrderBuyMotorbike extends BaseLive
{
    //TODO tudn
    public $engineno;
    public $keyword;
    public $showAdvancesearch =0 ;
    public $customerSelectedId;
    public $orderSelectedId;
    public $headCompany;
    public $headName;
    public $headAddress;
    public $headPhoneNumber;
    public $customerPrint;
    public $motorbikePrint;
    public $searchName;
    public $searchType;
    public $searchAddr;
    public $searchStatus = 2;
    public $searchSupplier;
    public $searchFromDate;
    public $searchToDate;
    public $dataExport;
    public $isVirtual = true;
    public $isReal = true;
    public $listSelected = [];
    public $key_name = "created_at";
    public $sortingName = "desc";
    public $sumTotalMoneyOriginal;
    public $sumTotalMoney;
    public $sumInstallmentMoney;
    protected $listeners = ['setfromDate', 'settoDate'];

    public function setfromDate($time)
    {
        $this->searchFromDate = date('Y-m-d', strtotime($time['fromDate']));
    }
    public function mount()
    {
        $this->dataExport = collect([]);
        $this->searchFromDate = $this->searchToDate = date('Y-m-d');
        $this->searchType = EOrder::TYPE_BANLE;
    }
    public function settoDate($time)
    {
        $this->searchToDate = date('Y-m-d', strtotime($time['toDate']));
    }
    public function render()
    {
        $query = $this->getQuery();
        $sumData = $this->getSumQuery()->get();
        $this->sumTotalMoney = $sumData[0]->sum_total_money;
        $this->sumTotalMoneyOriginal = $sumData[0]->sum_total_money_original;
        $this->sumInstallmentMoney = $sumData[0]->sum_installment_money;
        $data = $query->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage)->setPath(route('motorbikes.orders.index'));

        $this->updateUI();
        return view('livewire.motorbike.list-order-buy-motorbike', [
            'data' => $data,
        ]);
    }
    public function updateUI()
    {
        $this->dispatchBrowserEvent('setSelect2');
    }
    public function getQuery()
    {
        $query = Order::query()
            ->where('orders.category', EOrder::CATE_MOTORBIKE)
            ->where('orders.payment_method', EPaymentMethod::DIRECT)
            ->where('orders.type', '!=', EOrder::TYPE_NHAP)
            ->leftjoin('customers', 'orders.customer_id', '=', 'customers.id')
            ->leftjoin('ex_province', 'customers.city', '=', 'ex_province.province_code')
            ->leftjoin('ex_district', 'customers.district', '=', 'ex_district.district_code')
            ->leftjoin('ex_ward', 'customers.ward', '=', 'ex_ward.ward_code')
            ->leftjoin('order_details', 'orders.id', '=', 'order_details.order_id')
            ->leftjoin('hms_receive_plan', function ($join) {
                $join->on('hms_receive_plan.chassic_no', '=', 'order_details.chassic_no');
                $join->on('hms_receive_plan.engine_no', '=', 'order_details.engine_no');
            })
            ->leftjoin('users as seller', 'orders.seller', '=', 'seller.id')
            ->leftjoin('users as assembler', 'orders.assembler', '=', 'assembler.id')
            ->leftjoin('installment', 'installment.order_relation_id', '=', 'orders.id')
            //todo tudn add more balance
            ->leftjoin('view_customer_balance as v1', 'v1.id','=','orders.customer_id')
            //end todo
            ->select(
                'customers.id as customers_id',
                'customers.code as code',
                'customers.name as name',
                'customers.sex as sex',
                'customers.birthday as birthday',
                'customers.identity_code as cmt',
                'customers.phone as phone',
                'ex_province.name as city',
                'ex_district.name as district',
                'ex_ward.name as ward',
                'customers.address as address',
                'order_details.chassic_no as chassic_no',
                'order_details.engine_no as engine_no',
                'order_details.model_list as model_list',
                'order_details.model_code as model_code',
                'order_details.model_type as model_type',
                'hms_receive_plan.color_code as color_code',
                'order_details.color as color',
                'orders.status as status',
                'orders.isvirtual as isvirtual',
                'orders.type as type',

                //'orders.total_money as total_money',
                DB::raw('COALESCE(v1.remain_amount,0) as total_money'),

                'orders.created_at as created_at',
                'orders.order_type',
                'orders.id',
                'order_details.id as order_details_id',
                'seller.name as seller_name',
                'assembler.name as assembler_name',
                'order_details.actual_price as total_money_original',
                'installment.contract_number as contract_number',
                'installment.money as installment_money'
            );
        if ($this->searchName)
            $query->where('customers.name', 'like', '%' . trim($this->searchName) . '%');
        if ($this->searchType)
            $query->where('orders.type', '=', $this->searchType);
        if ($this->searchStatus)
            $query->where('orders.status', '=', $this->searchStatus);
        if ($this->searchAddr)
            $query->where('customers.address', 'like', '%' . trim($this->searchAddr) . '%');

        if (!empty($this->searchFromDate)) {
            $query->whereDate('orders.created_at', '>=', $this->searchFromDate);
        }
        if (!empty($this->searchToDate)) {
            $query->whereDate('orders.created_at', '<=', $this->searchToDate . ' 23:59:59');
        }
        if ($this->isVirtual && !$this->isReal) {
            $query->where('orders.isvirtual', true);
        }
        if (!$this->isVirtual && $this->isReal) {
            $query->where('orders.isvirtual', false);
        }

        //search theo engine no
        if (isset($this->engineno) && $this->engineno!="")  {
            # code...
            $query->where('order_details.engine_no','like','%'.$this->engineno.'%');
        }
        return $query;
    }
    public function getSumQuery()
    {
        $query = Order::query()
            ->where('orders.category', EOrder::CATE_MOTORBIKE)
            ->where('orders.payment_method', EPaymentMethod::DIRECT)
            ->where('orders.type', '!=', EOrder::TYPE_NHAP)
            ->leftjoin('customers', 'orders.customer_id', '=', 'customers.id')
            ->leftjoin('order_details', 'orders.id', '=', 'order_details.order_id')
            ->leftjoin('installment', 'installment.order_relation_id', '=', 'orders.id')
            //todo tudn add more balance
            ->leftjoin('view_customer_balance as v1', 'v1.id','=','orders.customer_id')
            //end todo
            ->select(
                //DB::raw('SUM(orders.total_money) as sum_total_money'),
                DB::raw('COALESCE(SUM(v1.remain_amount),0) as sum_total_money'),

                DB::raw('COALESCE(SUM(order_details.actual_price),0) as sum_total_money_original'),
                DB::raw('COALESCE(SUM(installment.money),0) as sum_installment_money'),
            );
        if ($this->searchName)
            $query->where('customers.name', 'like', '%' . trim($this->searchName) . '%');
        if ($this->searchType)
            $query->where('orders.type', '=', $this->searchType);
        if ($this->searchStatus)
            $query->where('orders.status', '=', $this->searchStatus);
        if ($this->searchAddr)
            $query->where('customers.address', 'like', '%' . trim($this->searchAddr) . '%');

        if (!empty($this->searchFromDate)) {
            $query->whereDate('orders.created_at', '>=', $this->searchFromDate);
        }
        if (!empty($this->searchToDate)) {
            $query->whereDate('orders.created_at', '<=', $this->searchToDate . ' 23:59:59');
        }
        if ($this->isVirtual && !$this->isReal) {
            $query->where('orders.isvirtual', true);
        }
        if (!$this->isVirtual && $this->isReal) {
            $query->where('orders.isvirtual', false);
        }
        return $query;
    }
    public function resetSearch()
    {
        $this->searchName = "";
        $this->searchType = "";
        $this->searchAddr = "";
        $this->searchStatus = "";
        $this->searchSupplier = "";
        $this->searchFromDate = "";
        $this->searchToDate = "";
        $this->emit('resetDateKendo');
    }
    public function updatedlistSelected()
    {
        if (count($this->listSelected) > 0) {
            $firstSelected = $this->listSelected[count($this->listSelected) - 1];
            $this->listSelected = [];
            $this->listSelected[] = $firstSelected;
            $this->customerSelectedId = explode('_', $firstSelected)[0];
            $this->orderSelectedId = explode('_', $firstSelected)[1];
        }
    }
    public function delete()
    {

        $order = Order::findOrFail($this->deleteId);
        if ($order->motorbikes->count() != 0) {
            $order->motorbikes()->update([
                'motorbikes.customer_id' => null,
                'motorbikes.status' => null,
            ]);
        }
        $order->details()->delete();
        $order->delete();
        $this->dispatchBrowserEvent('show-toast', ["type" => "success", "message" => "Xóa thành công."]);
    }
    public function export()
    {
        $this->updateUI();
        $query = $this->getQuery();
        $itemEX = $query->orderBy($this->key_name, $this->sortingName)->get();
        $mtocList = Mtoc::get();
        $exportBy = DB::table('users')->where('id', '=', auth()->id())->select('users.username')->first();
        $this->dataExport = $itemEX->map(function ($item) use ($mtocList, $exportBy) {
            $mtoc = $mtocList->where('model_code', $item->model_list,)
                ->where('type_code', $item->model_type)
                ->where('option_code', $item->model_code)
                ->where('color_name', $item->color)
                ->first();
            return [
                'code' => $item->code,
                'name' => $item->name,
                'phone' => $item->phone,
                'cmt' => $item->cmt,
                'birthday' => $item->birthday,
                'city' =>  $item->city,
                'district' =>  $item->district,
                'ward' =>  $item->ward,
                'address' =>  $item->address,
                'chassic_no' =>  $item->chassic_no,
                'engine_no' =>  $item->engine_no,
                'model_list' =>  $item->model_list,
                'model_code' =>  $item->model_code,
                'model_type' =>  $item->model_type,
                'mtoc_code' =>  empty($mtoc) ? '' : $mtoc->mtocd,
                'total_money' =>  $item->total_money,
                'sex' =>  $item->sex,
                'status' =>  $item->status,
                'type' =>  $item->type,
                'order_type' => $item->order_type,
                'seller_name' => $item->seller_name,
                'assembler_name' => $item->assembler_name,
                'exporter' => $exportBy->username
            ];
        });
        //$listOrder = Order::query()->where('category', '2')->get();
        if ($this->dataExport->count() == 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'warning', 'message' => 'Ko có bản ghi nào!']);
            $this->emit('close-modal-export');
        } else {
            return Excel::download(new ListOrderBuyMotorbikeExport($this->dataExport), 'dsdonhangxe_' . date('Y-m-d-His') . '.xlsx');
        }
    }
    public function exportOrder($idOrder)
    {
        $orderDetail = OrderDetail::where('id', $idOrder)->firstOrFail();
        $customer = $orderDetail->order->customer;
        $motorbike = $orderDetail->motorbike;
        $fileTemplatePath = public_path() . "/export-template/GiayBanXe.xlsx";
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $spreadsheet = $reader->load($fileTemplatePath);

        $sheet = $spreadsheet->getSheet(0);
        $headCompany = env('HEAD_COMPANY');
        $headName = env('HEAD_NAME');
        $headAddress = env('HEAD_ADDRESS');
        $headPhoneNumber = env('HEAD_PHONE_NUMBER');
        $sheet->setCellValue('D4', $headCompany);
        $sheet->setCellValue('E6', $headName);
        $sheet->setCellValue('H7', $headAddress);
        $sheet->setCellValue('G9', $headPhoneNumber);
        $sheet->setCellValue('J11', "Hôm nay, ngày " . Carbon::now()->day . " tháng " . Carbon::now()->month . " năm " . Carbon::now()->year);

        if ($customer) {
            $sheet->setCellValue('C14', $customer->name);
            $sheet->setCellValue('C15', $customer->address
                . (isset($customer->wardCustomer) ? ', ' . $customer->wardCustomer->name : '')
                . (isset($customer->districtCustomer) ? ', ' . $customer->districtCustomer->name : '')
                . (isset($customer->provinceCustomer) ? ', ' . $customer->provinceCustomer->name : ''));
            $sheet->setCellValue('C16', $customer->phone);
        }
        $sheet->setCellValue('C17', $headCompany . " Bán cho bên mua một chiếc xe máy");
        if ($motorbike) {
            $sheet->setCellValue('C18', $motorbike->model_list);
            $sheet->setCellValue('N18', $motorbike->color);
            $sheet->setCellValue('C19', $motorbike->chassic_no);
            $sheet->setCellValue('O19', $motorbike->engine_no);
        }

        $writer = new Writer\Xls($spreadsheet);
        $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . "GiayBanXe_" . Carbon::now()->format('YmdHis') . "xlsx" . '"');
        $response->headers->set('Cache-Control', 'max-age=0');
        return $response;
    }
    // public function printOrder($idOrder)
    // {
    //     $orderDetail = OrderDetail::where('id', $idOrder)->firstOrFail();
    //     $this->customer = $orderDetail->order->customer;
    //     $this->motorbike = $orderDetail->motorbike;
    //     $this->headCompany = env('HEAD_COMPANY');
    //     $this->headName = env('HEAD_NAME');
    //     $this->headAddress = env('HEAD_ADDRESS');
    //     $this->headPhoneNumber = env('HEAD_PHONE_NUMBER');
    //     return view('livewire.motorbike.in-thong-tin-motorbike');
    // }
}
