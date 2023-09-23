<?php

namespace App\Http\Livewire\Customer;

use App\Exports\ListCustomerExport;
use App\Http\Livewire\Base\BaseLive;
use Livewire\Component;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CNBHImport;
use App\Models\District;
use App\Models\Province;
use App\Models\Motorbike;
use DateTime;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;



class ListCustomer extends BaseLive
{
    public $searchName;
    public $searchPhone;
    public $searchBirthday;
    public $searchSex;
    public $searchAddress;
    public $searchEmail;
    public $searchIdentityCode;
    public $searchJob;
    public $fileCNBH;

    protected $listeners = ['settransactionDate'];
    public function settransactionDate($time)
    {
        $this->searchBirthday = date('Y-m-d', strtotime($time['transactionDate']));
    }
    public function render()
    {
        $query = Customer::query();
        if ($this->searchName)
            $query->where('name', 'like', '%' . trim($this->searchName) . '%');
        if ($this->searchEmail)
            $query->where('email', 'like', '%' . trim($this->searchEmail) . '%');
        if ($this->searchPhone)
            $query->where('phone', 'like', '%' . trim($this->searchPhone) . '%');
        if ($this->searchAddress)
            $query->where('address', 'like', '%' . trim($this->searchAddress) . '%');
        if ($this->searchJob)
            $query->where('job', 'like', '%' . trim($this->searchJob . '%'));
        if ($this->searchIdentityCode)
            $query->where('identity_code', 'like', '%' . trim($this->searchIdentityCode . '%'));
        if ($this->searchBirthday)
            $query->where('birthday', $this->searchBirthday);
        if ($this->searchSex)
            $query->where('sex', $this->searchSex);
        $this->dispatchBrowserEvent('setDateForDatePicker');
        $data = $query->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage);
        return view('livewire.customer.list-customer', compact('data'));
    }

    public function delete()
    {
        $isBought = DB::table('motorbikes')->where('motorbikes.customer_id', "=", $this->deleteId)->count();
        if ($isBought != 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Khách hàng đã mua xe nên không thể xoá']);
        } else {
            Customer::findOrFail($this->deleteId)->delete();
        }
    }

    public function resetSearch()
    {
        $this->searchName = null;
        $this->searchPhone = null;
        $this->searchBirthday = null;
        $this->searchSex = null;
        $this->searchAddress = null;
        $this->searchEmail = null;
        $this->searchIdentityCode = null;
        $this->searchJob = null;
        $this->emit('resetDateRangerKendo');
    }
    public function importCNBH()
    {
        $this->validate([
            'fileCNBH' => 'required'
        ], [
            'fileCNBH.required' => 'Hãy chọn file để import',
        ]);
        try {
            DB::beginTransaction();
            $collection = Excel::toCollection(new CNBHImport, $this->fileCNBH, null);
            $sheetData = $collection[0];
            $countImported = 0;
            $dataImport = [];
            $provinceList = collect(Province::get()->toArray());
            $districtList = collect(District::get()->toArray());
            foreach ($sheetData as $key => $row) {
                // $arrayName = [];
                // if (!empty($row[0])) {
                //     $arrayName[] = trim($row[0]);
                // }
                // if (!empty($row[2])) {
                //     $arrayName[] = trim($row[2]);
                // }
                // if (!empty($row[1])) {
                //     $arrayName[] = trim($row[1]);
                // }
                $name = trim($row[0]);
                $index = $key + 1;
                if(empty($name)) {
                    $name = 'Chưa có tên';
                }
                $phone = str_replace(' ', '',(empty($row[14]) ? ((str_starts_with($row[13], '0') ? '' : 0) . $row[13]) : (str_starts_with($row[14], '0') ? '' : 0) . $row[14]));
                $sex = null;

                $provinceId = null;
                $districtId = null;
                if (empty($row[22])){
                    $provinceId = '';
                    $districtId = '';
                }
                else {
                    $province = $provinceList->first(function ($item, $key) use ($row) {
                        return str_contains(mb_strtoupper($item['name']), mb_strtoupper(str_replace('TP.', '', $row[19])));
                    });
                    if ($province) {
                        if (empty($row[22])){
                            $districtId = '';
                        } else {
                            $provinceId = $province['province_code'];
                            $district = $districtList->first(function ($item, $key) use ($row, $provinceId) {
                                return str_contains(mb_strtoupper($item['name']), mb_strtoupper(str_replace('TP.', '', $row[22]))) && $item['province_code'] == $provinceId;
                            });
                            if ($district)
                            $districtId = $district['district_code'];
                        }
                    }
                }



                $strSex = Str::upper(trim($row[7]));
                if ($strSex == 'NAM' || $strSex == "MALE")
                    $sex = 1;
                else
                    $sex = 2;

                if(gettype($row[6]) == 'string') {
                    $birthday = ($row[6]);
                    $birthday = DateTime::createFromFormat('d/m/Y', $birthday);
                    $birthday = $birthday->format('Y-m-d');
                } else {
                    $birthday = intval($row[6]);
                    $birthday = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($birthday)->format('Y-m-d') ;
                }
                // $birthday = intval($row[6]) ;
                // $birthday = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($birthday)->format('Y-m-d') ;

                $model = [
                    'name' => $name,
                    'email' => null,
                    'image' => null,
                    'job' => $row[8],
                    'code' => 'CO_' . $phone,
                    'sex' => $sex,
                    'address' => $row[16],
                    'district' => $districtId,
                    'city' => $provinceId,
                    'birthday' => $birthday,
                    'identity_code' => null,
                    'country' => $row[23],
                    'phone' => $phone
                ];
                $customer = Customer::updateOrCreate([
                    'phone' => $phone
                ], $model);
                Motorbike::where('customer_phone', $phone)->update([
                    'customer_id' => $customer->id
                ]);
                // $dataImport[] = $model;
                $countImported++;
            }
            // foreach (array_chunk($dataImport, 1000) as $data) {
            //     Customer::upsert(
            //         $data,
            //         ['phone'],
            //         ['name', 'email', 'image', 'job', 'code', 'sex', 'address', 'district', 'city', 'birthday', 'identity_code', 'country']
            //     );
            // }
            


            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => "Dữ liệu CNBH (" . $countImported . " item) import thành công"]);
            $this->emit('close-modal-import-cnbh');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => "Dữ liệu CNBH import thất bại"]);
            return;
        }
    }
    public function export()
    {
        $query = Customer::query();
        if ($this->searchName)
            $query->where('name', 'like', '%' . trim($this->searchName) . '%');
        if ($this->searchEmail)
            $query->where('email', 'like', '%' . trim($this->searchEmail) . '%');
        if ($this->searchPhone)
            $query->where('phone', 'like', '%' . trim($this->searchPhone) . '%');
        if ($this->searchAddress)
            $query->where('address', 'like', '%' . trim($this->searchAddress) . '%');
        if ($this->searchJob)
            $query->where('job', 'like', '%' . trim($this->searchJob . '%'));
        if ($this->searchIdentityCode)
            $query->where('identity_code', 'like', '%' . trim($this->searchIdentityCode . '%'));
        if ($this->searchBirthday)
            $query->where('birthday', $this->searchBirthday);
        if ($this->searchSex)
            $query->where('sex', $this->searchSex);
        $this->dispatchBrowserEvent('setDateForDatePicker');
        $data = $query->orderBy($this->key_name, $this->sortingName)->get();

        return Excel::download(new ListCustomerExport(
            $data
        ), 'baocaokhophutung_' . date('Y-m-d-His') . '.xlsx');
    }
    public function downloadExample()
    {
        return Storage::disk('public')->download('CNBH_KH_Template.xlsx');
    }
}
