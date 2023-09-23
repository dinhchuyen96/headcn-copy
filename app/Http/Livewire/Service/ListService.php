<?php

namespace App\Http\Livewire\Service;

use App\Exports\OdersExport;
use App\Http\Livewire\Base\BaseLive;
use App\Models\Order;
use App\Models\Accessory;
use App\Models\Customer;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Bus;
use Maatwebsite\Excel\Facades\Excel;
use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Models\User;
use App\Models\WorkContent;
use Carbon\Carbon;
use Dflydev\DotAccessData\Data;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;
use App\Enum\EUserPosition;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Writer as Writer;

class ListService extends BaseLive
{
    public $customerSelectedId;
    public $orderSelectedId;
    public $perPage = 10;
    public $searchName;
    public $searchAddess;
    public $searchStatus = 2;
    public $searchTerm;
    public $fromDate;
    public $searchMotorNumber;
    public $toDate;
    public $countRecords;
    public $deleteId = '';
    const PAID = 1;
    const UNPAID = 2;
    const WAITING_PROGRESSING = 3;
    const CANCELLED = 4;
    const PENDING_CANCELLATION = 5;
    public $statusOders;
    public $listSelected = [];
    public $sortingName = "desc";
    public $key_name = "created_at";
    public $searchDigest = 3;
    public $searchTimes;
    public $phone_number;
    public $work_status;

    public $customerPhone;
    protected $listeners = ['setfromDate', 'settoDate'];

    public function mount()
    {
        $this->fromDate = $this->toDate = date('Y-m-d');
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
        $this->searchMotorNumber = trim($this->searchMotorNumber);
        $this->searchTerm  = trim($this->searchTerm);
        if ($this->reset) {
            $this->searchTerm = null;
            $this->searchAddess = null;
            $this->searchStatus = null;
            $this->fromDate = null;
            $this->toDate =  null;
            $this->searchMotorNumber =  null;
            $this->searchDigest = 3;
            $this->emit('resetDateKendo');
        }
        $query = $this->getQuery();

        $data = $query->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage)->setPath(route('dichvu.dsdonhang.index'));
        $this->dispatchBrowserEvent('setSelect2');

        // dd($data);
        $sumMoney = $this->getQuery()->sum("total_money");

        return view('livewire.service.list-service', compact('data', 'sumMoney'));
    }
    public function getQuery()
    {
        $query = Order::query()->with(['customer', 'motorbike', 'details.motorbike', 'periodic', 'repairBill', 'repairTask'])
            ->whereIn('category', [3, 4])->where(function ($q) {
                $q->orWhere('type', '<>', 3);
                $q->orWhereNull('type');
            });

        if ($this->key_name) {
            $query->orderBy($this->key_name, $this->sortingName);
        }
        if ($this->customerPhone) {
            $customer = Customer::where('phone', $this->customerPhone)->first();
            if ($customer)
                $query->where('customer_id', $customer->id);
        }
        if ($this->searchTerm) {
            $query->whereHas('motorbike', function (Builder $query) {
                $query->whereRaw("LOWER(chassic_no) LIKE LOWER('%{$this->searchTerm}%')");
                $query->orWhereRaw("LOWER(engine_no) LIKE LOWER('%{$this->searchTerm}%')");
            });
        }
        if ($this->searchMotorNumber) {
            $query->whereHas('motorbike', function (Builder $query) {
                $query->whereRaw("LOWER(motor_numbers) LIKE LOWER('%{$this->searchMotorNumber}%')");
            });
        }
        if ($this->searchAddess) {
            $query->whereHas('customer', function (Builder $query) {
                $query->whereRaw("LOWER(address) LIKE LOWER('%{$this->searchAddess}%')");
            });
        }
        if ($this->searchStatus) {
            $query->where('status', $this->searchStatus);
        }
        if (empty($this->searchMotorNumber)) {
            if ($this->fromDate) {
                $query->where('created_at', '>=', $this->fromDate . ' 00:00:00');
            }

            if ($this->toDate) {
                $query->where('created_at', '<=', $this->toDate . ' 23:59:59');
            }
        }
        if($this->phone_number) {
            $query->whereHas('customer', function (Builder $query) {
                $query->whereRaw("LOWER(phone) LIKE LOWER('%{$this->phone_number}%')");
            });
        }
        if($this->work_status) {
            $query->where('work_status',intval($this->work_status));
        }
        if ($this->searchDigest) {
            $query->where('category', '=', $this->searchDigest);
            if ($this->searchDigest == 3) //KTĐK
            {
                if ($this->searchTimes) {
                    $query->whereHas('periodic', function (Builder $query) {
                        $query->where('periodic_level', $this->searchTimes);
                    });
                }
            }
        }
        return $query;
    }

    public static function valueToName($value)
    {
        $result = "";
        switch ($value) {
            case self::PAID:
                $result = 'Đã thanh toán';
                break;
            case self::UNPAID:
                $result = 'Chưa thanh toán';
                break;
            case self::WAITING_PROGRESSING:
                $result = 'Chờ xử lý';
                break;
            case self::CANCELLED:
                $result = 'Đã hủy';
                break;
            case self::PENDING_CANCELLATION:
                $result = 'Chờ xử lý hủy';
                break;
            default:
                $result = $value;
                break;
        }
        return $result;
    }
    public function deleteId($id)
    {
        $this->deleteId = $id;
    }
    public function delete()
    {
        $order = Order::where('id', $this->deleteId)->with('details')->first();
        if ($order->status == 1)
        {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Đơn hàng này đã thanh toán. Không thể xóa!!!']);
        }
        else {
            $orderDetail = $order->details->where('status', EOrderDetail::STATUS_SAVED)
            ->where('admin_id', auth()->id())
            ->where('type', EOrderDetail::TYPE_BANLE)
            ->where('category', EOrder::CATE_REPAIR);
            foreach ($orderDetail as $key => $item) {
                $accessory = Accessory::where('id', $item->product_id)
                    ->where('position_in_warehouse_id', $item->position_in_warehouse_id)
                    ->first();
                if ($accessory) {
                    $accessory->quantity += $item->quantity;
                    $accessory->save();
                }
            }
            $order->delete();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Xóa dịch vụ thành công']);
        }

    }
    public function export()
    {
        $query = $this->getQuery();
        $listOrders = $query->orderBy($this->key_name, $this->sortingName)->get();
        $this->countRecords = $listOrders->count();
        if ($this->countRecords == 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Không có bản ghi nào!']);
            $this->emit('close-modal-export');
        } else {
            $dataForWorkContent = WorkContent::leftJoin('repair_task', function ($q) {
                $q->on('work_content.id', '=', 'repair_task.work_content_id');
                $q->whereNull('repair_task.deleted_at');
            })
                ->groupBy('work_content.id', 'work_content.name')
                ->select(
                    'work_content.id as work_content_id',
                    'work_content.name',
                    DB::raw('ROUND(SUM(repair_task.price*(100-repair_task.promotion)/100)) as total')
                )->get();
            $dataForFixer = User::leftJoin('repair_task', function ($q) {
                $q->on('users.id', '=', 'repair_task.id_fixer_main');
                $q->whereNull('repair_task.deleted_at');
            })
                ->whereIn('users.positions', [EUserPosition::NV_KI_THUAT, EUserPosition::NV_KIEM_TRA, EUserPosition::NV_SUA_CHUA])
                ->groupBy('users.id', 'users.name', 'users.email')
                ->select(
                    'users.id as user_id',
                    'users.name',
                    'users.email',
                    DB::raw('ROUND(SUM(repair_task.price*(100-repair_task.promotion)/100)) as total')
                )->get();
            $data = [
                'fromDate' => $this->fromDate,
                'toDate' => $this->toDate,
                'odersExportService' => $listOrders,
                'dataForWorkContent' => $dataForWorkContent,
                'dataForFixer' => $dataForFixer
            ];
            return Excel::download(new OdersExport($data), 'dsdonhang_' . date('Y-m-d-His') . '.xlsx');
        }
    }
    public function updatedFromDate()
    {
        $this->validateOnly([
            'fromDate' => 'before_or_equal:' . date('Y-m-d'),
        ], [], [

            'fromDate' => 'Ngày nhập không được vượt quá ngày hiện tại'
        ]);
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
    public function exportOrder($idOrder)
    {
        $order = Order::where('id', $idOrder)->with('details')->firstOrFail();
        $customer = $order->customer;
        $repairBill = null;
        $repairPreodic = null;
        if ($order->category == EOrderDetail::CATE_REPAIR) {
            $repairBill = $order->repairBill;
        }
        if ($order->category == EOrderDetail::CATE_MAINTAIN) {
            $repairPreodic = $order->periodic;
        }
        $repairTask = $order->repairTask->take(20);
        $orderDetail = $order->details->where('status', EOrderDetail::STATUS_SAVED)
            ->where('type', EOrderDetail::TYPE_BANLE)
            ->filter(function ($item, $key) {
                return $item->category == EOrderDetail::CATE_REPAIR || $item->category == EOrderDetail::CATE_MAINTAIN;
            })
            ->take(20);
        $fileTemplatePath = public_path() . "/export-template/PhieuSuaChua.xlsx";
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $spreadsheet = $reader->load($fileTemplatePath);

        $headName = env('HEAD_NAME');
        $headAddress = env('HEAD_ADDRESS');
        $headPhoneNumber = env('HEAD_PHONE_NUMBER');
        $headHotline = env('HEAD_HOTLINE');
        $headEmail = env('HEAD_EMAIL');
        $spreadsheet->getActiveSheet()->setCellValue('A2', $headName);
        $spreadsheet->getActiveSheet()->setCellValue('C3', $headAddress);
        $spreadsheet->getActiveSheet()->setCellValue('D4', $headPhoneNumber);
        $spreadsheet->getActiveSheet()->setCellValue('C5', $headHotline);
        $spreadsheet->getActiveSheet()->setCellValue('C6', $headEmail);
        $spreadsheet->getActiveSheet()->setCellValue('AH2', "STT: " . $idOrder);
        if ($customer) {
            $spreadsheet->getActiveSheet()->setCellValue('A8', $customer->name);
            $spreadsheet->getActiveSheet()->setCellValue('D9', $customer->phone);
            $spreadsheet->getActiveSheet()->setCellValue('K8',  $customer->address
                . (isset($customer->wardCustomer) ? ', ' . $customer->wardCustomer->name : '')
                . (isset($customer->districtCustomer) ? ', ' . $customer->districtCustomer->name : '')
                . (isset($customer->provinceCustomer) ? ', ' . $customer->provinceCustomer->name : ''));
        }
        if ($repairBill) {
            $motobike = $repairBill->motorbike;
            if ($motobike) {
                $spreadsheet->getActiveSheet()->setCellValue('C10', $motobike->model_type);
                $spreadsheet->getActiveSheet()->setCellValue('C11', $motobike->motor_numbers);

                $spreadsheet->getActiveSheet()->setCellValue('M10', $motobike->chassic_no);
                $spreadsheet->getActiveSheet()->setCellValue('M11', $motobike->engine_no);
                $spreadsheet->getActiveSheet()->setCellValue('AE11', empty($motobike->sell_date) ? Carbon::createFromFormat('Y-m-d', $motobike->buy_date)->format('d/m/Y') : Carbon::createFromFormat('Y-m-d', $motobike->sell_date)->format('d/m/Y'));
            }

            $in_factory_date = empty($repairBill->in_factory_date) ? '' : Carbon::createFromFormat('Y-m-d H:s:i', $repairBill->in_factory_date)->format('d/m/Y H:s:i');
            $spreadsheet->getActiveSheet()->setCellValue('AG7', $in_factory_date);
            $spreadsheet->getActiveSheet()->setCellValue('AD10', number_format($repairBill->km));
            $spreadsheet->getActiveSheet()->setCellValue('A14', $repairBill->content_request);
        }

        if ($repairPreodic) {
            $motobike = $repairPreodic->motorbike;
            if ($motobike) {
                $spreadsheet->getActiveSheet()->setCellValue('C10', $motobike->model_type);
                $spreadsheet->getActiveSheet()->setCellValue('C11', $motobike->motor_numbers);

                $spreadsheet->getActiveSheet()->setCellValue('M10', $motobike->chassic_no);
                $spreadsheet->getActiveSheet()->setCellValue('M11', $motobike->engine_no);
                $spreadsheet->getActiveSheet()->setCellValue('AE11', empty($motobike->sell_date) ? Carbon::createFromFormat('Y-m-d', $motobike->buy_date)->format('d/m/Y') : Carbon::createFromFormat('Y-m-d', $motobike->sell_date)->format('d/m/Y'));
            }

            $in_factory_date = empty($repairPreodic->created_at) ? '' : Carbon::createFromFormat('Y-m-d H:s:i', $repairPreodic->created_at)->format('d/m/Y H:s:i');
            $spreadsheet->getActiveSheet()->setCellValue('AG7', $in_factory_date);
            $spreadsheet->getActiveSheet()->setCellValue('AD10', number_format($repairPreodic->km));
            //$spreadsheet->getActiveSheet()->setCellValue('A14', $repairPreodic->content_request);
        }

        if ($repairTask) {
            $indexRowStart = 20;
            foreach ($repairTask as $key => $item) {
                $spreadsheet->getActiveSheet()->setCellValue('B' . $indexRowStart, $item->content);
                $spreadsheet->getActiveSheet()->setCellValue('U' . $indexRowStart, number_format($item->price));
                $spreadsheet->getActiveSheet()->setCellValue('AC' . $indexRowStart, $item->promotion);
                $spreadsheet->getActiveSheet()->setCellValue('AH' . $indexRowStart, number_format(round($item->price * (100 - $item->promotion) / 100)));
                $indexRowStart++;
            }
        }

        if ($orderDetail) {
            $indexRowStart = 43;
            foreach ($orderDetail as $key => $item) {
                $spreadsheet->getActiveSheet()->setCellValue('B' . $indexRowStart, $item->accessorie->name);
                $spreadsheet->getActiveSheet()->setCellValue('K' . $indexRowStart, $item->accessorie->code);
                $spreadsheet->getActiveSheet()->setCellValue('O' . $indexRowStart, number_format($item->price));
                $spreadsheet->getActiveSheet()->setCellValue('U' . $indexRowStart, number_format($item->quantity));
                $spreadsheet->getActiveSheet()->setCellValue('AE' . $indexRowStart, $item->promotion);
                if ($item->actual_price) {
                    $spreadsheet->getActiveSheet()->setCellValue('AJ' . $indexRowStart, number_format($item->actual_price));
                } else {
                    $totalPrice = round($item->price * $item->quantity * (100 - $item->promotion) / 100);
                    $spreadsheet->getActiveSheet()->setCellValue('AJ' . $indexRowStart, number_format($totalPrice));
                }
                $indexRowStart++;
            }
        }
        $spreadsheet->getActiveSheet()->setCellValue('AE63', number_format($order->total_money));
        $spreadsheet->getActiveSheet()->setCellValue('R85', "Trong trường hợp đặc biệt, nếu xe của quý khách gặp sự cố mà không thể đến được, xin quý khách vui lòng gọi điện trực tiếp đến số điện " . $headHotline);

        // $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        // $writer->save($fileOutputPath);

        $writer = new Writer\Xls($spreadsheet);

        $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . "PhieuSuaChua_" . Carbon::now()->format('YmdHis') . "xlsx" . '"');
        $response->headers->set('Cache-Control', 'max-age=0');
        return $response;
    }
}
