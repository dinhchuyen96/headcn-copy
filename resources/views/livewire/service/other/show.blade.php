<div>
    <div class="page-heading">
        <h1 class="page-title">Xem dịch vụ khác</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fa fa-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Xem dịch vụ khác</li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thông tin dịch vụ</div>
            </div>
            <div class="ibox-body">

                        <div class="form-group row">
                            <label for="customer" class="col-2 col-form-label ">Khách hàng <span class="text-danger">
                                    *</span></label>
                            <div class="col-4">
                                <select id="customer" name="customer" disabled
                                    wire:model.lazy="customer" class="custom-select form-control">
                                    <option value="">--- Chọn khách hàng ---</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">
                                            {{ $customer->name . ' - ' . $customer->phone }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <label for="technicalId" class="col-2 col-form-label ">NV kĩ thuật</label>
                            <div class="col-4">
                                <select id="fixerId" name="fixerId" wire:model.lazy="fixerId" disabled
                                    class="custom-select form-control">
                                    <option value="">--Chọn--</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                <div class="table-responsive">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-8">
                                <h2>Danh sách dịch vụ</h2>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col" style="width:200px">Dịch vụ</th>
                                <th scope="col">Nội dung</th>
                                <th scope="col" style="width:150px">Tiền công(VND)</th>
                                <th scope="col" style="width:150px">Khuyến mãi(%)</th>
                                <th scope="col" style="width:150px">Thành tiền(VND)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($services))
                                @foreach ($services as $key => $service)
                                    <tr>
                                        <td>
                                            <select class="form-control select2-box list_service" disabled
                                                onchange="changeListService({{ $service }}, event)"
                                                id="list_service_{{ $service }}"
                                                wire:model="list_service.{{ $service }}">
                                                <option hidden value="">Chọn dịch vụ</option>
                                                @foreach ($o_service_list[$service] as $value)
                                                    <option value="{{ $value['id'] }}">
                                                        {{ $value['title'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" disabled
                                                wire:model="service_content.{{ $service }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control task-input" min="0" disabled
                                                onkeypress="return onlyNumberKey(event)"
                                                wire:model="service_price.{{ $service }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control task-input" min="0" disabled
                                                onkeypress="return onlyNumberKey(event)"
                                                wire:model="service_promotion.{{ $service }}">
                                        </td>
                                        <td>
                                            <input type="text" readonly class="form-control"
                                                wire:model="service_total.{{ $service }}">
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center text-danger">Chưa có dữ liệu</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive pt-5">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-8">
                                <h2>Danh sách phụ tùng</h2>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width:170px">Mã PT</th>
                                <th style="width:220px">Vị trí kho</th>
                                <th style="width:200px">Tên PT</th>
                                <th>Mã NCC</th>
                                <th>Số lượng</th>
                                <th>Đơn giá</th>
                                <th>Khuyến mãi(%)</th>
                                <th>Thành tiền</th>
                                <th>Giá in hóa đơn</th>
                                <th>Giá thực tế</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($accessories))
                                @foreach ($accessories as $key => $accessory)
                                    <tr>
                                        <td>
                                            <select class="form-control select2-box accessory_code" disabled
                                                id="accessory_code_{{ $accessory }}"
                                                wire:model="accessory_code.{{ $accessory }}">
                                                <option hidden value="">Chọn Mã phụ tùng</option>
                                                @foreach ($accessories_list[$accessory] as $value)
                                                    <option value="{{ $value }}">
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control select2-box accessory_warehouse_pos" disabled
                                                id="accessory_warehouse_pos_{{ $accessory }}"
                                                wire:model="accessory_warehouse_pos.{{ $accessory }}">
                                                <option hidden value="">Chọn Vị trí kho</option>
                                                @foreach ($positions_list[$accessory] as $value)
                                                    <option value="{{ $value['id'] }}">
                                                        {{ $value['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" readonly class="form-control" disabled
                                                wire:model="accessory_name.{{ $accessory }}">
                                        </td>
                                        <td>
                                            <input type="text" readonly class="form-control" disabled
                                                wire:model="accessory_supplier.{{ $accessory }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" disabled
                                                wire:model="accessory_quantity.{{ $accessory }}">
                                        </td>
                                        <td>
                                            <input type="number" readonly class="form-control" disabled
                                                wire:model="accessory_price.{{ $accessory }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" disabled
                                                wire:model="accessory_promotion.{{ $accessory }}">
                                        </td>
                                        <td>
                                            <input type="text" readonly class="form-control"
                                                wire:model="accessory_total.{{ $accessory }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" disabled
                                                wire:model="accessory_price_vat.{{ $accessory }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" disabled
                                                wire:model="accessory_price_actual.{{ $accessory }}">
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="11" class="text-center text-danger">Chưa có dữ liệu</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="form-group row justify-content-center btn-group-mt">
                    <div>
                        <a href="{{ route('xemay.dichvukhac.index') }}" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i>
                            Trở lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
