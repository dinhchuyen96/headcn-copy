<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thông tin dịch vụ</div>
            </div>
            <div class="ibox-body">
                <div wire:loading class="loader"></div>
                <div class="form-group row" style="">
                    <label for="customer" class="col-1 col-form-label ">Khách hàng <span class="text-danger">
                            *</span></label>
                    <div class="col-3">
                        <div wire:ignore>
                            <select name='customerPhone' id="customerPhone"
                                data-ajax-url="{{ route('customers.getCustomerByPhoneOrNameWithId.index') }}"
                                class="custom-select">
                            </select>
                        </div>
                        {{-- <select id="customer" name="customer" {{ $disabled_customer ? 'disabled' : '' }}
                            wire:model.lazy="customer" class="custom-select select2-box form-control">
                            <option value="">--- Chọn khách hàng ---</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">
                                    {{ $customer->name . ' - ' . $customer->phone }}
                                </option>
                            @endforeach
                        </select> --}}
                        @error('customer')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <label for="PhoneNumber" class="col-1 col-form-label">Số điện thoại <span
                                class="text-danger">*</span></label>
                        <div class="col-3">
                            <input id="PhoneNumber" name="PhoneNumber"
                                placeholder="Số điện thoại" type="number" wire:model.lazy='phone'
                                 class="form-control" required="required">
                            @error('phone')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    <label for="Birthday" class="col-1 col-form-label">Ngày sinh <span
                            class="text-danger">*</span></label>
                    <div class="col-3">
                        <input type="date" id="birthday" class="form-control"
                            max='{{ date('Y-m-d') }}'  wire:model.lazy="birthday">
                        @error('birthday')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>

                </div>

                <form>
                    <div class="form-group row mt-1">
                        <label for="CustomerName" class="col-1 col-form-label">Tên khách hàng <span
                                class="text-danger">*</span></label>
                        <div class="col-3">
                            <input id="CustomerName" name="CustomerName" placeholder="Tên khách hàng" type="text"
                                wire:model.lazy='name' class="form-control" required="required"
                                >
                            @error('name')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="Email" class="col-1 col-form-label">Email </label>
                        <div class="col-3">
                            <input id="Email" name="Email" placeholder="Email" type="text" wire:model.lazy='email'
                                class="form-control">
                        </div>
                    </div>
                    <div>
                        <div class="form-group row">
                            <label for="CustomerName" class="col-1 col-form-label">Địa chỉ <span
                                    class="text-danger">*</span></label>
                            <div class="col-3">
                                <input id="address" name="address" placeholder="Địa chỉ" type="text"
                                    wire:model.lazy='address' class="form-control" required="required">
                                @error('address')
                                    @include('layouts.partials.text._error')
                                @enderror
                            </div>
                            <label for="CustomerDistrict" class="col-1 col-form-label ">Thành phố/ Tỉnh <span
                                    class="text-danger">*</span></label>
                            <div class="col-3">
                                <select wire:model="province_id" id="supplyProvince" class="form-control select2-box"
                                    >
                                    <option hidden>Chọn Thành phố/ Tỉnh</option>
                                    @foreach ($province as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ $key == $province_id ? 'selected' : '' }}>
                                            {{ $item }}</option>
                                    @endforeach
                                </select>
                                @error('province_id')
                                    @include('layouts.partials.text._error')
                                @enderror
                            </div>
                            <label for="CustomerProvince" class="col-1 col-form-label ">Quận/ Huyện <span
                                    class="text-danger">*</span></label>
                            <div class="col-3">
                                <select wire:model="district_id" id="supplyDistrict" class="custom-select select2-box"
                                    >
                                    <option hidden>Chọn Quận/ Huyện</option>
                                    @foreach ($district as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ $key == $district_id ? 'selected' : '' }}>
                                            {{ $item }}</option>
                                    @endforeach
                                </select>
                                @error('district_id')
                                    @include('layouts.partials.text._error')
                                @enderror
                            </div>
                            <!-- <div wire:loading class="loader"></div> -->
                        </div>
                        <div wire:loading class="loader"></div>
                        <div class="form-group row">
                            <label for="CustomerProvince" class="col-1 col-form-label ">Phường/ Xã <span
                                    class="text-danger">*</span></label>
                            <div class="col-3">
                                <select wire:model="ward_id" id="supplyWard" class="custom-select select2-box"
                                    >
                                    <option hidden>Chọn Phường/ Xã</option>
                                    @foreach ($ward as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ $key == $ward_id ? 'selected' : '' }}>
                                            {{ $item }}</option>
                                    @endforeach
                                </select>
                                @error('ward_id')
                                    @include('layouts.partials.text._error')
                                @enderror
                            </div>

                            <label for="Sex" class="col-1 col-form-label">Giới tính </label>
                        <div class="col-3">
                            <select class="form-control" wire:model.lazy='sex' >
                                <option value="1">Nam</option>
                                <option value="2">Nữ</option>
                            </select>
                        </div>
                        <label for="Job" class="col-1 col-form-label">Nghề nghiệp <span
                                class="text-danger">*</span></label>
                        <div class="col-3">
                            <input id="Job" placeholder="Nghề nghiệp" type="text" wire:model.lazy='job'
                                class="form-control">
                            @error('job')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="sellerId" class="col-1 col-form-label ">NV bán hàng</label>
                        <div class="col-3">
                            <select id="sellerId" name="sellerId"
                                wire:model.lazy="sellerId" class="custom-select select2-box form-control">
                                <option value="">--Chọn--</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                        <label for="fixerId" class="col-1 col-form-label ">NV kĩ thuật<span class="text-danger"> *</span></label>
                        <div class="col-3">
                            <select id="fixerId" name="fixerId" wire:model.lazy="fixerId"
                                 class="custom-select select2-box form-control">
                                <option value="">--Chọn--</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('fixerId')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <label for="accountingDate" class="col-1 col-form-label">Ngày hạch toán</label>
                        <div class="col-3">
                            <input type="date" id="accountingDate" class="form-control"
                                max='{{ date('Y-m-d') }}' wire:model.lazy="accountingDate">
                            @error('accountingDate')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                </form>

                <div class="table-responsive pt-5">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-8">
                                <h2>Danh sách dịch vụ</h2>
                            </div>
                            <div class="col-sm-4 text-right pb-2">
                                <button type="button" wire:click.prevent="addService({{ $i }})"
                                    class="btn btn-info add-new"><i class="fa fa-plus"></i> Thêm mới</button>
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
                                <th style="width:50px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($services))
                                @foreach ($services as $key => $service)
                                    <tr>
                                        <td>
                                            <select class="form-control select2-box list_service"
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
                                            @error("list_service.$service")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="text" class="form-control"
                                                wire:model="service_content.{{ $service }}">
                                            @error("service_content.$service")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="number" class="form-control task-input" min="0"
                                                onchange="changeServiceValue({{ $service }})"
                                                onkeypress="return onlyNumberKey(event)"
                                                wire:model="service_price.{{ $service }}">
                                            @error("service_price.$service")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="number" class="form-control task-input" min="0"
                                                onchange="changeServiceValue({{ $service }})"
                                                onkeypress="return onlyNumberKey(event)"
                                                wire:model="service_promotion.{{ $service }}">
                                        </td>
                                        <td>
                                            <input type="text" readonly class="form-control"
                                                wire:model="service_total.{{ $service }}">
                                        </td>
                                        <td class="align-middle text-center">
                                            <a class="delete"
                                                wire:click.prevent="removeService({{ $key }})"
                                                data-toggle="tooltip" data-original-title="Xóa">
                                                <i class="fa fa-remove"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center text-danger">Chưa có dữ liệu</td>
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
                            <div class="col-sm-4 text-right">
                                <button type="button" wire:click.prevent="addAccessory({{ $j }})"
                                    class="btn btn-info add-new"><i class="fa fa-plus"></i> Thêm mới</button>
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
                                <th style="width:50px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($accessories))
                                @foreach ($accessories as $key => $accessory)
                                    <tr>
                                        <td>
                                            <select class="form-control select2-box accessory_code"
                                                onchange="changeAccessoryCode({{ $accessory }}, event)"
                                                id="accessory_code_{{ $accessory }}"
                                                wire:model="accessory_code.{{ $accessory }}">
                                                <option hidden value="">Chọn Mã phụ tùng</option>
                                                @foreach ($accessories_list[$accessory] as $value)
                                                    <option value="{{ $value }}">
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error("accessory_code.$accessory")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>

                                            <select class="form-control select2-box accessory_warehouse_pos"
                                                onchange="changeWarehousePos({{ $accessory }}, event)"
                                                id="accessory_warehouse_pos_{{ $accessory }}"
                                                wire:model="accessory_warehouse_pos.{{ $accessory }}">
                                                <option hidden value="">Chọn Vị trí kho</option>
                                                @foreach ($positions_list[$accessory] as $value)
                                                    <option value="{{ $value['id'] }}">
                                                        {{ $value['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error("accessory_warehouse_pos.$accessory")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="text" readonly class="form-control"
                                                wire:model="accessory_name.{{ $accessory }}">
                                        </td>
                                        <td>
                                            <input type="text" readonly class="form-control"
                                                wire:model="accessory_supplier.{{ $accessory }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control"
                                                onkeypress="return onlyNumberKey(event)"
                                                onchange="changeAccessoryValue({{ $accessory }})"
                                                wire:model="accessory_quantity.{{ $accessory }}">
                                            @if (!empty($accessory_available_quantity_root[$accessory]))
                                                @if ($accessory_available_quantity[$accessory] >= 0)
                                                    <p class="text-info">Còn lại :
                                                        {{ $accessory_available_quantity[$accessory] }}</p>
                                                @else
                                                    <p class="text-info">Không đủ bán</p>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <input type="number" class="form-control"
                                                wire:model="accessory_price.{{ $accessory }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control"
                                                onkeypress="return onlyNumberKey(event)"
                                                onchange="changeAccessoryValue({{ $accessory }})"
                                                wire:model="accessory_promotion.{{ $accessory }}">
                                        </td>
                                        <td>
                                            <input type="text" readonly class="form-control"
                                                wire:model="accessory_total.{{ $accessory }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control"
                                                onkeypress="return onlyNumberKey(event)"
                                                wire:model="accessory_price_vat.{{ $accessory }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control"
                                                onkeypress="return onlyNumberKey(event)"
                                                wire:model="accessory_price_actual.{{ $accessory }}">
                                        </td>
                                        <td class="align-middle text-center">
                                            <a class="delete"
                                                wire:click.prevent="removeAccessory({{ $key }})"
                                                data-toggle="tooltip" data-original-title="Xóa">
                                                <i class="fa fa-remove"></i></a>
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

                <div class="form-group row">
                    <div class="col-12 text-center">
                        <a href="{{ route('xemay.dichvukhac.index') }}" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i>
                            Trở lại
                        </a>
                        <button wire:click.prevent="store" type="button" {{ $disabled ? 'disabled' : '' }}
                            class="btn btn-primary">
                            <i class="fa fa-plus"></i>
                            Lưu thông tin
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {


        setSelect2Customer();
        $('#customerPhone').on('change', function(e) {
            var data = $('#customerPhone').select2("val");
            window.livewire.emit('changeCustomer', {
                customer_id: data,
            });
        });
        $('#fixerId').on('change', function(e) {
            var data = $('#fixerId').select2("val");
            @this.set('fixerId', data);
        });
        $('#supplyProvince').on('change', function(e) {
            var data = $('#supplyProvince').select2("val");
            @this.set('province_id', data);
        });
        $('#supplyDistrict').on('change', function(e) {
            var data = $('#supplyDistrict').select2("val");
            @this.set('district_id', data);
        });
        $('#supplyWard').on('change', function(e) {
            var data = $('#supplyWard').select2("val");
            @this.set('ward_id', data);
        });
        $('#paymentMethod').on('change', function(e) {
            var data = $('#paymentMethod').select2("val");
            @this.set('paymentMethod', data);
        });
        $('#installmentCompany').on('change', function(e) {
            var data = $('#installmentCompany').select2("val");
            @this.set('installmentCompany', data);
        });
    });
    document.addEventListener('setDatePicker', function() {
        setDatePickerUI();
    });
    document.addEventListener('livewire:load', function() {
        setDatePickerUI();
    });

    function setSelect2Customer() {
            let ajaxUrl = $('#customerPhone').data("ajaxUrl");
            $('#customerPhone').select2({
                ajax: {
                    url: ajaxUrl,
                    data: function(params) {
                        var query = {
                            search: params.term,
                        }
                        return query;
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                },
                placeholder: '--- Chọn khách hàng ---',
            });
    };

    function changeServiceValue(index) {
        window.livewire.emit('countServicePrice', index);
    }

    function changeAccessoryValue(index) {
        window.livewire.emit('countAccessoryPrice', index);
    }

    function changeAccessoryCode(index, event) {
        let value = $("#accessory_code_" + index + " option:selected").val();
        window.livewire.emit('changeAccessoryCode', {
            index: index,
            value: value
        });
    }

    function changeListService(index, event) {
        let value = $("#list_service_" + index + " option:selected").val();
        window.livewire.emit('changeListService', {
            index: index,
            value: value
        });
    }

    function changeWarehousePos(index, event) {
        let value = $("#accessory_warehouse_pos_" + index + " option:selected").val();
        window.livewire.emit('changeWarehousePos', {
            index: index,
            value: value
        });
    }

    function setDatePickerUI() {
        $("#accountingDate").kendoDatePicker({
            format: "dd/MM/yyyy"
        });
        var accountingDate = $("#accountingDate").data("kendoDatePicker");
        accountingDate.bind("change", function() {
            var value = this.value();
            if (value != null) {
                var datestring = moment(value).format('YYYY-MM-DD');
                @this.set('accountingDate', datestring);
            }
        });

        $("#birthday").kendoDatePicker({
            format: "dd/MM/yyyy"
        });
        var birthday = $("#birthday").data("kendoDatePicker");
        birthday.bind("change", function() {
            var value = this.value();
            if (value != null) {
                var datestring = moment(value).format('YYYY-MM-DD');
                @this.set('birthday', datestring);
            }
        });

    };
</script>
