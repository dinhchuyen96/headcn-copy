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
use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Enum\EMotorbike;
use App\Models\Mtoc;

class MtocImport implements ToCollection, WithStartRow, SkipsEmptyRows, WithValidation
{
    use SkipsErrors;
    /**
     * @param Collection $collection
     */
    use SkipsErrors;
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $rowNo = 1;
            $id = trim($row[0]);
            $dm = trim($row[1]);
            $ten = trim($row[2]);
            $ploai = trim($row[3]);
            $gia = trim($row[4]);
            $mamau = trim($row[5]);
            $tenmau = trim($row[6]);
            $isCheckid = Mtoc::Where("mtocd", $id)->count();
            $priceCheck = Mtoc::where('model_code', $ten)
            ->where('type_code', $ploai)
            ->where('option_code', $dm)
            ->where('color_code', $mamau)
            ->where('color_name', $tenmau)->count();
            if ($isCheckid != 0) {
                session()->put('error', 'Dòng ' . $rowNo . ': Mã MTOC đã tồn tại');
            }
            elseif($priceCheck !=0 ) {
                session()->put('error', 'Dòng ' . $rowNo . ': Mã MTOC đã tồn tại');
            }
            else{
                $item = new Mtoc();
                $item->mtocd = $id ;
                $item->model_code = $ten ;
                $item->type_code = $ploai ;
                $item->option_code = $dm ;
                $item->color_code = $mamau ;
                $item->color_name = $tenmau ;
                $item->suggest_price = $gia ;
                $item->save();

            }

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
            '2' => 'required',
            '3' => 'required',
            '4' => 'required|integer|gt:0',
            '5' => 'required',
            '6' => 'required',
        ];
    }
    public function customValidationMessages()
    {
        return [
            '0.required' => 'Cột mã MTOC Không đc bỏ trống',
            '1.required' => 'Cột danh mục  không được bỏ trống',
            '2.required' => 'Cột tên đời xe được bỏ trống',
            '3.required' => 'Cột phân loại xe không được bỏ trống',
            '4.required' => 'Giá đề xuất bắt buộc',
            '4.integer' => 'Giá đề xuất kiểu số',
            '4.gt' => 'Giá đề xuất lớn hơn 0',
            '5.required' => 'Cột mã màu xe không được bỏ trống',
            '6.required' => 'Cột tên màu xe không được bỏ trống',
        ];
    }
}
