<?php

namespace App\Imports;

use App\Models\Motorbike;
use App\Models\District;
use App\Models\MasterData;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Province;
use App\Models\Customer;
use App\Models\Ward;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Http\Livewire\Motorbike\BanBuonMotorbike;
use Illuminate\Http\Request;
use App\Enums\MotobikeStatus;


class ChuyenKhoPhuTungImport implements ToCollection, WithStartRow, SkipsEmptyRows, WithValidation
{
    use SkipsErrors;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {

    }

    public function startRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [
            '0' => 'required',
            '1' => 'required'
        ];
    }
    public function customValidationMessages()
    {
        return [
            '0.required' => 'Cột Số khung không được bỏ trống',
            '1.required' => 'Cột Số máy không được bỏ trống'
        ];
    }
}
