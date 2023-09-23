<?php

namespace App\Imports;

use App\Models\PositionInWarehouse;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class ValidatePhuTungNhapImport implements ToCollection, WithStartRow, WithValidation
{
    public function collection(Collection $rows)
    {
        $codes = Supplier::all()->pluck('code')->unique()->toArray();
        $warehouseNames = Warehouse::all()->pluck('name')->unique()->toArray();
        $positionNames = PositionInWarehouse::all()->pluck('name')->unique()->toArray();
        $errors = [];
        if (count($rows) > 1) {
            $rows = $rows->slice(1);
            foreach ($rows as $key => $row) {
                if (!in_array(trim($row[0]), $codes)) {
                    $error = ['Mã NCC' => 'Mã NCC không tồn tại'];
                    $failures[] = new Failure($key + 1, 'Mã NCC', $error, []);
                    throw new \Maatwebsite\Excel\Validators\ValidationException(
                        \Illuminate\Validation\ValidationException::withMessages($error),
                        $failures
                    );
                }

                if (!in_array(trim($row[12]), $warehouseNames)) {
                    $error = ['Tên kho' => 'Tên kho không tồn tại'];
                    $failures[] = new Failure($key + 1, 'Tên kho', $error, []);
                    throw new \Maatwebsite\Excel\Validators\ValidationException(
                        \Illuminate\Validation\ValidationException::withMessages($error),
                        $failures
                    );
                }

                if (!in_array(trim($row[13]), $positionNames)) {
                    $error = ['Vị trí kho' => 'Vị trí kho không tồn tại'];
                    $failures[] = new Failure($key + 1, 'Vị trí kho', $error, []);
                    throw new \Maatwebsite\Excel\Validators\ValidationException(
                        \Illuminate\Validation\ValidationException::withMessages($error),
                        $failures
                    );
                }
            }
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
            '0' => [
                'required',
            ],
            '1' => 'required|date_format:"d/m/Y"',
            '4' => 'required',
            '5' => 'required',
            '6' => 'required|numeric|min:0',
            '10' => 'required|numeric|min:0',
            '12' => [
                'required',
            ],
            '13' => [
                'required',
            ]
        ];
    }
    public function customValidationMessages()
    {
        return [
            '0.required' => 'Mã nhà cung cấp không được bỏ trống',
            '0.exists' => 'Mã nhà cung cấp không tồn tại',
            '1.required' => 'Ngày mua hàng không được trống',
            '1.date_format' => 'Ngày mua hàng phải định dạng dd/mm/YYYY',
            '4.required' => 'Cột mã phụ tùng không được bỏ trống',
            '5.required' => 'Cột tên phụ tùng không được bỏ trống',
            '6.required' => 'Cột số lượng phụ tùng không được bỏ trống',
            '6.numeric' => 'Cột số lượng không phải kiểu sổ',
            '6.min' => 'Cột số lượng phải lớn hơn 0',
            '10.required' => 'Cột đơn giá không được bỏ trống',
            '10.numeric' => 'Cột đơn giá hông phải kiểu sổ',
            '10.min' => 'Cột đơn giá phải lớn hơn 0',
            '12.required' => 'Tên kho không được bỏ trống',
            '12.exists' => 'Tên kho không tồn tại',
            '13.required' => 'Vị trí kho không được bỏ trống',
            '13.exists' => 'Vị trí kho không tồn tại',
        ];
    }
}
