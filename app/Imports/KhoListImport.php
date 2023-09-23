<?php

namespace App\Imports;
use App\Models\Warehouse;
use App\Models\PositionInWarehouse;
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
use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Enum\EMotorbike;
use Carbon\Carbon;

class KhoListImport implements ToCollection, WithStartRow, SkipsEmptyRows, WithValidation
{
    use SkipsErrors;
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $warehouseInfo = [];
        foreach ($collection as $row) {
            $row[0] = trim($row[0]); //name
            $row[1] = trim($row[1]); // name vi tri

            $warehouseName[] = $row[0];
            $positionName[] = $row[1];
        }
        $warehouseName = array_unique(array_filter($warehouseName));
        $rowNo = 1;
        foreach ($collection as $row) {
            $warhouse = Warehouse::where('name', $row[0])->where('deleted_at', 'is', null)->first();
            if ($warhouse) {
                $warehouse_id =$warhouse->id;
                $position = PositionInWarehouse::where('name', $row[1])->where('deleted_at', 'is', null)
                ->where('warehouse_id',$warehouse_id)
                ->first();
                if ($position) {
                    session()->put('error', 'Dòng ' . $rowNo . 'Vị trí kho đã tồn tại');
                    return 0;
                }
            }
            $rowNo +=1;
        }

        //else save to db
        foreach ($collection as $row) {
            $warehouse = Warehouse::firstOrCreate([
                'name' => $row[0],
                'deleted_at' => null
            ], [
                'name' => $row[0],
                'address' => '',
                'created_at' =>Carbon::today(),
                'updated_at' =>Carbon::today(),
            ]);

            $position = new PositionInWarehouse();
            $position->warehouse_id= $warehouse->id;
            $position->name = $row[1];
            $position->created_at = Carbon::today();
            $position->updated_at = Carbon::today();
            $position->save();
        }
    }

    public function startRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [
            '0' => 'required',
            '1' => 'required',
        ];
    }
    public function customValidationMessages()
    {
        return [
            '0.required' => 'Cột tên kho không được bỏ trống',
            '1.required' => 'Tên vị trí không được bỏ trống'
        ];
    }
}
