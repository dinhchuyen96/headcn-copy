<?php

namespace App\Imports;

use App\Models\Accessory;
use App\Models\Customer;
use App\Models\District;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class CNBHImport implements ToCollection, WithStartRow
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
}
