<?php

namespace App\Imports;

use App\Models\Accessory;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Supplier;
use App\Models\PositionInWarehouse;
use App\Models\CategoryAccessory;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use App\Enum\EOrder;
use Illuminate\Support\Facades\DB;


class DanhMucPhuTungImport implements ToCollection, WithStartRow, WithValidation
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $partno = [];
        $parentpartno = [];
        $collectionValid = collect([]);
        foreach ($collection as $key => $row) {
            $partno[] = $row[0];
            $parentpartno[] = $row[3];

            $warehouseImport = trim($row[6]);
            $positionInWarehouseImport = trim($row[7]);
            $positionInWarehouse = PositionInWarehouse::join('warehouse', 'warehouse.id', 'position_in_warehouse.warehouse_id')
                ->where('warehouse.name', $warehouseImport)
                ->where('position_in_warehouse.name', $positionInWarehouseImport)
                ->select('position_in_warehouse.id as position_in_warehouse_id', 'warehouse.id as warehouse_id')
                ->first();
            if (!$positionInWarehouse) {
                $error = ['Tên kho hoặc vị trí kho' => 'Tên kho hoặc vị trí kho không tồn tại'];
                $failures[] = new Failure($key + 1, 'Tên kho hoặc vị trí kho', $error, []);
                throw new \Maatwebsite\Excel\Validators\ValidationException(
                    \Illuminate\Validation\ValidationException::withMessages($error),
                    $failures
                );
            }
            $row[6] = $positionInWarehouse->warehouse_id;
            $row[7] = $positionInWarehouse->position_in_warehouse_id;

            $collectionValid->push($row);
        }
        $partno = array_unique(array_filter($partno));
        $parentpartno = array_unique(array_filter($parentpartno));

        //
        foreach ($collectionValid as $row) {
          $code = $row[0]  ;
          $name =$row[1];
          $unit =$row[2];
          $parentcode =$row[3];
          $parentname =$row[4];
          $changerate =$row[5];
          $warehouseid =$row[6];
          $positioninwarehouseid =$row[7];
          $netprice =$row[8];

          $partInfo = CategoryAccessory::where('code',$code)
          ->where('deleted_at',null)
          ->where('warehouse_id',$warehouseid)
          ->where('position_in_warehouse_id',$positioninwarehouseid)
          ->get()->first();

          if(!$partInfo){ $partInfo = new CategoryAccessory();}

          //get parent part if exist
          $parentpartInfo = CategoryAccessory::where('code',$parentcode)
          ->where('deleted_at',null)
          ->where('warehouse_id',$warehouseid)
          ->where('position_in_warehouse_id',$positioninwarehouseid)
          ->get()->first();
          if($parentpartInfo){
              $parentunit = $parentpartInfo->unit;
          }else $parentunit = null;

          $partInfo->code =$code;
          $partInfo->name =$name;
          $partInfo->unit =$unit;
          $partInfo->parentcode = $parentcode;
          $partInfo->parentunit =$parentunit;
          $partInfo->warehouse_id =$warehouseid;
          $partInfo->position_in_warehouse_id =$positioninwarehouseid;
          $partInfo->changerate = $changerate ;
          $partInfo->netprice =$netprice;
          $partInfo->updated_at = reFormatDate(now(), 'Y-m-d');
          $partInfo->save();
        }

    }

    public function startRow(): int
    {
        return 2;
    }

    //|exists:suppliers,code
    public function rules(): array
    {
        return [
            '0' => 'required',
            '1' => 'required',
            '2' => 'required',
            '5' => 'integer|gt:0',
            '6' => 'required',
            '7' => 'required',
            '8' => 'required|integer|gt:0',
        ];
    }
    public function customValidationMessages()
    {
        return [
            '0.required' => 'Mã phụ tùng bắt buộc',
            '1.required' => 'Tên phụ tùng bắt buộc',
            '2.required' => 'Đơn vị tính bắt buộc',
            '5.integer' => 'Tỉ lệ quy đổi phải kiểu số',
            '5.gt' => 'Tỉ lệ quy đổi phải lớn hơn 0',
            '6.required' => 'Kho mặc định bắt buộc',
            '7.required' => 'Vị trí bắt buộc',
            '8.required' => 'Giá đề xuất bắt buộc',
            '8.integer' => 'Giá đề xuất kiểu số',
            '8.gt' => 'Giá đề xuất lớn hơn 0',
        ];
    }
}
