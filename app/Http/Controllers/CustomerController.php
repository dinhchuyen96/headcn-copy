<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\District;
use App\Models\Province;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Route;
use DateTime;

class CustomerController extends Controller
{
    public function index()
    {
        return view('khachhang.dskhachhang');
    }

    public function show($id)
    {
        $data = Customer::findOrFail($id);
        $districtList = District::query()->pluck('name', 'district_code');
        $provinceList = Province::query()->pluck('name', 'province_code');

        return view('khachhang.show', compact('data', 'districtList', 'provinceList'));
    }
    public function giftChange($id)
    {
        $customer = Customer::findOrFail($id);
        return view('khachhang.gift-change', compact('customer'));
    }


    public function create()
    {
        $now = DateTime::createFromFormat('U.u', microtime(true))->modify('+ 7 hour');
        $defaultCustomerCode = 'CO_' . substr($now->format("ymdhisu"), 0, -3);
        $districtList = District::query()->pluck('name', 'district_code');
        $provinceList = Province::query()->pluck('name', 'province_code');
        return view('khachhang.themmoi', compact('districtList', 'provinceList', 'defaultCustomerCode'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'nullable|email|unique:customers,email',
            'phone' => 'required|unique:customers,phone',
            'code' => 'required|unique:customers,code',
            'birthday' => 'required',
            'identity_code',
        ], [], [
            'name' => 'Tên khách hàng',
            'email' => 'Email',
            'phone' => 'Số điện thoại',
            'address' => 'Địa chỉ',
            'code' => 'Mã khách hàng',
            'identity_code' => 'CMT/CCCD',
            'birthday' => 'Ngày sinh'

        ]);
        $parameter = $request->all();
        $parameter['birthday'] = Carbon::createFromFormat('d/m/Y', $parameter['birthday'])->toDateString();
        Customer::create($parameter);

        return redirect()->route('customers.index');
    }

    public function edit($id)
    {
        $data = Customer::findOrFail($id);
        $districtList = District::query()->pluck('name', 'district_code');
        $provinceList = Province::query()->pluck('name', 'province_code');

        return view('khachhang.edit', compact('data', 'districtList', 'provinceList'));
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'nullable|email|unique:customers,email,' . $id,
            'phone' => 'required|unique:customers,phone,' . $id,
            'address',
            'birthday' => 'required',
            'code' => 'required|unique:customers,code,' . $id,
            'identity_code',
        ], [], [
            'name' => 'Tên khách hàng',
            'email' => 'Email',
            'phone' => 'Số điện thoại',
            'address' => 'Địa chỉ',
            'code' => 'Mã khách hàng',
            'identity_code' => 'CMT/CCCD',
            'birthday' => 'Ngày sinh'
        ]);

        $customer = Customer::findOrFail($id);
        $parameter = $request->all();
        $parameter['birthday'] = Carbon::createFromFormat('d/m/Y', $parameter['birthday'])->toDateString();
        $customer->update($parameter);

        return redirect()->route('customers.index');
    }
    public function getCustomerByPhoneOrName(Request $request)
    {
        $search = $request['search'];
        $listCustomer = Customer::whereNotNull('phone')->whereNotNull('name')
            ->where('phone', 'like', '%' . $search . '%')
            ->orWhere('name', 'like', '%' . $search . '%')
            ->select('id', 'phone', 'name')->get();
        $listCustomer = $listCustomer->map(function ($item, $key) {
            return [
                'id' => $item->phone,
                'text' => $item->name . ' - ' . $item->phone,
            ];
        });
        return $listCustomer;
    }

    public function getCustomerByPhoneOrNameWithId(Request $request)
    {
        $search = $request['search'];
        $listCustomer = Customer::whereNotNull('phone')->whereNotNull('name')
            ->where('phone', 'like', '%' . $search . '%')
            ->orWhere('name', 'like', '%' . $search . '%')
            ->select('id', 'phone', 'name')->get();
        $listCustomer = $listCustomer->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'text' => $item->name . ' - ' . $item->phone,
            ];
        });
        return $listCustomer;
    } 
}
