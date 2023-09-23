<?php

namespace App\Http\Livewire\Component;

use Livewire\Component;
use App\Enum\EUserPosition;
use App\Models\RepairTask;
use App\Models\Order;
use App\Models\User;
use App\Enum\EOrder;
use App\Enum\ERepairTask;
use App\Enum\EWorkContent;
use App\Models\Supplier;
use App\Service\Community;
use App\Models\WorkContent;
use Illuminate\Support\Facades\Auth;

class InputRepair extends Component
{

    // Mục data list
    public $content; // Nội dung công việc
    public $price; // Tiền công
    public $promotion; // Tiền khuyến mãi
    public $totalPrice; // Thành tiền
    public $mainFixerId; // nhân viên đc chọn
    public $payment; // Chi tiền
    public $processCompany; // Đơn vị thực hiện
    // Trạng thái page
    public $isShow = false;
    public $isEdit = false;
    public $isAdd = false;

    // Trạng thái action
    public $isAddMode = false;
    public $isEditMode = false;


    public $orderId;
    public $contentEdit, $priceEdit, $promotionEdit, $totalPriceEdit, $mainFixerIdEdit, $paymentEdit, $processCompanyEdit;
    public $itemEditID;
    //Dữ liệu data select
    public $listFixer;
    public $updateFixer;
    public $listContent;

    public $isOutWorkRepair, $isOutWorkRepairEdit = false;


    protected $listeners = [
        'addNew',
        'loadListInput' => 'render',
        'updateCode',
        'updatePO',
        'resetListInput' => 'resetInput',
        'addInputRow',
        'updateSupPhone',
        'setServiceFixerId',
        'setMainFixerId'
    ];

    public function mount()
    {
        $this->listFixer = User::whereIn('positions', [EUserPosition::NV_KI_THUAT, EUserPosition::NV_KIEM_TRA, EUserPosition::NV_SUA_CHUA])->get();
        $this->listContent = WorkContent::select('id', 'name', 'type')->get();

        if ($this->isAdd)
            $this->promotion = 0;
    }
    public function setServiceFixerId($Fixer)
    {
        $this->mainFixerId = $Fixer;
    }
    public function render()
    {
        $repairTasks = collect([]);
        // Tính toán thành tiền khi add mới
        if ($this->price && isset($this->promotion)) {
            $total = round((int) $this->price * (100 - $this->promotion) / 100);
            if ($total >= 0)
                $this->totalPrice = round((int) $this->price * (100 - $this->promotion) / 100);
        }
        // Tính toán thành tiền khi edit
        if ($this->priceEdit && $this->promotionEdit) {
            $totalEdit = round((int)$this->priceEdit * (100 - $this->promotionEdit) / 100);
            if ($totalEdit >= 0)
                $this->totalPriceEdit = round((int) $this->priceEdit * (100 - $this->promotionEdit) / 100);
        }

        // Lấy dữ liệu danh sách công việc sửa chữa
        if ($this->isShow) {
            // Nếu là man hinh show
            $repairTasks = RepairTask::with('user')->where('orders_id', $this->orderId)->where('status', ERepairTask::SAVED)->get();
            
        }
        if ($this->isEdit) {
            // Nếu là edit
            $orderId = $this->orderId;
            $repairTasks = RepairTask::with('user')->where(
                function ($q) use ($orderId) {
                    $q->where('orders_id', $orderId);
                    $q->Where('status', ERepairTask::SAVED);
                }
            )
                ->orWhere(
                    function ($q) {
                        $q->whereNull('orders_id');
                        $q->where('status', ERepairTask::DRAFT);
                        $q->where('admin_id', auth()->id());
                    }
                )->get();
        }
        if ($this->isAdd) {
            // Nếu là add
            $repairTasks = RepairTask::with(['user', 'supply'])->where('status', ERepairTask::DRAFT)->where('admin_id', auth()->id())->get();
        }
        $repairTasks = $repairTasks->map(function ($item, $key) {
            return (object) [
                'id' => $item->id,
                'content' => $item->content,
                'price' => number_format(Community::getAmount($item->price)),
                'promotion' => $item->promotion,
                'totalPrice' => number_format(round($item->price * (100 - $item->promotion) / 100)),
                'totalPriceEdit' => number_format(round($item->price * (100 - $item->promotion) / 100)),
                'fixerName' => $item->user->name,
                'workContent' => $item->workContent->name,
                'payment' => $item->payment,
                'processCompany' => $item->process_company,
                'isOutWork' => $item->workContent->type == EWorkContent::OUT
            ];
        });
        $this->updateUI();
        return view('livewire.component.input-repair', ['repairTasks' => $repairTasks]);
    }
    public function updateUI()
    {
        $this->dispatchBrowserEvent('setSelect2');
    }
    public function addItem()
    {
        $this->validate([
            'content' => 'required',
            'price' => 'required|numeric|min:1|max:9999999999',
            'payment' => $this->isOutWorkRepair ? 'required|numeric|min:0|max:9999999999' : '',
            'processCompany' => $this->isOutWorkRepair ? 'required' : '',
            'promotion' => 'required|numeric|min:0|max:100',
            'mainFixerId' => 'required'
        ], [
            'content.required' => 'Nội dung công việc bắt buộc nhập',
            'price.required' => 'Tiền công bắt buộc nhập',
            'price.min' => 'Tiền công tối thiếu là 1',
            'price.max' => 'Tiền công tối đa là 9999999999',

            'payment.required' => 'Tiền công bắt buộc nhập',
           // 'payment.min' => 'Tiền công tối thiếu là 1',
            'payment.max' => 'Tiền công tối đa là 9999999999',

            'promotion.required' => 'Khuyến mãi bắt buộc nhập',
            'promotion.min' => 'Khuyến mãi tối thiếu là 0',
            'promotion.max' => 'Khuyến mãi tối đa là 100',
            'promotion.numeric' => 'Khuyến mãi phải là số',
            'mainFixerId.required' => 'Nhân viên sửa chữa là bắt buộc',
            'processCompany.required' => 'Đơn vị gia công là bắt buộc'
        ]);
        $repair = new RepairTask;
        if ($this->isOutWorkRepair) {
            $repair->payment = $this->payment;
            $repair->process_company = $this->processCompany;
        }
        $repair->work_content_id = $this->content;
        $repair->price = $this->price;
        $repair->status = ERepairTask::DRAFT;
        $repair->admin_id = auth()->id();
        $repair->promotion = $this->promotion;
        $repair->id_fixer_main = $this->mainFixerId;
        $repair->save();
        $this->isAddMode = false;
        //$this->render();
        $this->resetInputFields();
        $this->emitUp('enableButtonParentTask');
    }
    public function editItem($id)
    {
        $this->emitUp('disableButtonParentTask');
        $this->isEditMode = true;
        $this->itemEditID = $id;
        $repair = RepairTask::findOrFail($id);
        $this->contentEdit = $repair->work_content_id;
        $this->mainFixerIdEdit = $repair->id_fixer_main;
        $this->priceEdit = $repair->price;
        $this->promotionEdit = $repair->promotion;
        $this->paymentEdit = $repair->payment;
        $this->processCompanyEdit = $repair->process_company;
        $this->totalPriceEdit = round($this->priceEdit * (100 - $this->promotionEdit) / 100);

        $workSelected = $this->listContent->first(function ($item, $key) {
            return $item->id == $this->contentEdit;
        });
        $this->isOutWorkRepairEdit = $workSelected->type == EWorkContent::OUT;
    }
    public function updateItem($id)
    {
        $this->validate([
            'contentEdit' => 'required',
            'priceEdit' => 'required|numeric|min:1|max:9999999999',
            'paymentEdit' =>  $this->isOutWorkRepairEdit ? 'required|numeric|min:0|max:9999999999' : '',
            'processCompanyEdit' =>  $this->isOutWorkRepairEdit ? 'required' : '',
            'promotionEdit' => 'required|numeric|min:0|max:100',
            'mainFixerIdEdit' => 'required'
        ], [
            'contentEdit.required' => 'Nội dung công việc bắt buộc nhập',
            'priceEdit.required' => 'Tiền công bắt buộc nhập',
            'priceEdit.min' => 'Tiền công tối thiếu là 1',
            'priceEdit.max' => 'Tiền công tối đa là 9999999999',

            'paymentEdit.required' => 'Tiền công bắt buộc nhập',
           // 'paymentEdit.min' => 'Tiền công tối thiếu là 1',
            'paymentEdit.max' => 'Tiền công tối đa là 9999999999',

            'promotionEdit.required' => 'Khuyến mãi bắt buộc nhập',
            'promotionEdit.min' => 'Khuyến mãi tối thiếu là 0',
            'promotionEdit.max' => 'Khuyến mãi tối đa là 100',
            'mainFixerIdEdit.required' => 'Nhân viên sửa chữa là bắt buộc',
            'processCompanyEdit.required' => 'Đơn vị gia công là bắt buộc'
        ]);

        $this->itemEditID = $id;
        $repair = RepairTask::findOrFail($id);
        if ($this->isOutWorkRepairEdit) {
            $repair->payment = $this->paymentEdit;
            $repair->process_company = $this->processCompanyEdit;
        } else {
            $repair->payment = null;
            $repair->process_company = null;
        }
        if ($repair->status == ERepairTask::SAVED) {
            $orders = Order::where('id', $this->orderId)->first();
            $orders->total_money = $orders->totalPriceForGeneralRepair();
            $orders->save();
            $workOldSelected = $this->listContent->first(function ($item, $key) use ($repair) {
                return $item->id == $repair->work_content_id;
            });
            $isOutOldWorkRepair = empty($workOldSelected) ? false : $workOldSelected->type == EWorkContent::OUT;
            //Nếu chuyển từ công việc ngoài thành công việc trong sẽ xóa hóa đơn chi
            if ($isOutOldWorkRepair && !$this->isOutWorkRepairEdit) {
                $orderPayment = $repair->orderPayment;
                if ($orderPayment) {
                    $orderPayment->delete();
                }
            }
            //Nếu chuyển từ công việc trong thành công việc ngoài sẽ tạo hóa đơn chi
            if (!$isOutOldWorkRepair && $this->isOutWorkRepairEdit) {
                $supply = Supplier::updateOrCreate([
                    'name' => $this->processCompanyEdit,
                    'code' => $this->processCompanyEdit,
                ], [
                    'phone' => null,
                    'email' => null,
                    'url' => null,
                    'address' => null,
                    'province_id' => null,
                    'district_id' => null,
                    'ward_id' => null,
                ]);
                $orderPayment = new Order;
                $orderPayment->customer_id = null;
                $orderPayment->total_money = $this->paymentEdit;
                $orderPayment->total_items = 0;
                $orderPayment->category = EOrder::CATE_REPAIR;
                $orderPayment->status = EOrder::STATUS_UNPAID;
                $orderPayment->type = EOrder::TYPE_NHAP;
                $orderPayment->order_type = EOrder::ORDER_TYPE_BUY;
                $orderPayment->motorbikes_id = null;
                $orderPayment->created_by = Auth::user()->id;
                $orderPayment->supplier_id = $supply->id;
                $orderPayment->save();
                $repair->supply_id = $supply->id;
                $repair->order_payment_id = $orderPayment->id;
            }
        }
        $repair->work_content_id = $this->contentEdit;
        $repair->price = $this->priceEdit;
        $repair->promotion = $this->promotionEdit;
        $repair->id_fixer_main = $this->mainFixerIdEdit;
        $repair->save();
        $this->itemEditID = '';
        $this->resetInputFields();
        $this->isEditMode = false;
        if ($this->isEdit) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Cập nhật công việc sửa chữa thành công']);
        }
        $this->emitUp('enableButtonParentTask');
    }

    public function delete($id)
    {
        $this->itemEditID = '';
        $repairTask = RepairTask::findOrFail($id);
        $repairTask->delete();
        if ($repairTask->status == ERepairTask::SAVED) {
            $orders = Order::where('id', $this->orderId)->first();
            $orders->total_money = $orders->totalPriceForGeneralRepair();
            $orders->save();
        }
        if ($this->isEdit) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Xóa công việc sửa chữa thành công']);
        }
    }
    public function cancelNew()
    {
        $this->isAddMode = false;
        $this->isEditMode = false;
        $this->emitUp('enableButtonParentTask');
    }
    public function cancel()
    {
        $this->itemEditID = '';
        $this->emitUp('enableButtonParentTask');
    }
    public function addNew()
    {
        $this->emitUp('disableButtonParentTask');
        $this->isAddMode = true;
        if ($this->isEdit) {
        }
    }
    public function resetInputFields()
    {
        $this->content = null;
        $this->price = null;
        $this->promotion = 0;
        $this->totalPrice = null;
        $this->isOutWorkRepair = false;
        $this->payment = null;
        $this->processCompany = null;
        $this->isOutWorkRepairEdit = false;
        $this->paymentEdit = null;
        $this->processCompanyEdit = null;
    }
    public function updatedmainFixerId($value)
    {
        $this->updateFixer = $value;
    }

    public function updatedcontent()
    {
        $workSelected = $this->listContent->first(function ($item, $key) {
            return $item->id == $this->content;
        });
        $this->isOutWorkRepair = $workSelected->type == EWorkContent::OUT;
    }
    public function updatedcontentEdit()
    {
        $workSelected = $this->listContent->first(function ($item, $key) {
            return $item->id == $this->contentEdit;
        });
        $this->isOutWorkRepairEdit = $workSelected->type == EWorkContent::OUT;
    }
}
