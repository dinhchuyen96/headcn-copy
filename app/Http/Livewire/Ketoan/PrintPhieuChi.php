<?php

namespace App\Http\Livewire\Ketoan;

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
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Writer as Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PrintPhieuChi extends BaseLive
{
    public $data;
    public function render()
    {
        return view('livewire.ketoan.print-phieu-chi', ['data' => $this->data]);
    }
}
