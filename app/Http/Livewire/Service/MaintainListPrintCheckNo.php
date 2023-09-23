<?php

namespace App\Http\Livewire\Service;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Log;

use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Enum\KMCheckStep;
use App\Enum\ERepairTask;
use App\Enum\EUserPosition;

use App\Models\Accessory;
use App\Models\District;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Periodic;
use App\Models\Motorbike;
use App\Models\OrderDetail;
use App\Models\Province;
use App\Models\RepairTask;
use App\Models\PositionInWarehouse;
use App\Models\User;
use App\Models\WorkContent;

class MaintainListPrintCheckNo extends Component
{
    public $motorbikeId;
    public $motorbike;
    public $checkDate;
    public $sellDate;
    public $km;
    public $checkNo;

    public function mount()
    {
        $checkNoPara = $_GET['checkNo'];
        $kmPara = $_GET['km'];
        $checkDatePara = $_GET['checkDate'];
        $motorbikeIdPara = $_GET['motorbikeId'];
        if ($checkNoPara) {
            if ($checkNoPara >= 1 && $checkNoPara <= 6) {
                $this->checkNo = $checkNoPara;
            }
        }
        if ($kmPara) {
            if ($kmPara >= KMCheckStep::ZERO && $checkNoPara <= KMCheckStep::SIX) {
                $this->km = str_split((string)$kmPara);
            }
        }
        if ($checkDatePara) {
            try {
                $this->checkDate = Carbon::parse($checkDatePara);
            } catch (\Exception $ex) {
                $this->checkDate = Carbon::now();
            }
        }
        if ($motorbikeIdPara) {
            $this->motorbike = Motorbike::where('id', $motorbikeIdPara)->with('customer')->first();
            if ($this->motorbike) {
                if ($this->motorbike->sell_date) {
                    $this->sellDate = Carbon::parse($this->motorbike->sell_date ?? $this->motorbike->buy_date);
                }
            }
        }
    }

    public function render()
    {

        return view('livewire.service.maintain-list-print-check-no');
    }
}
