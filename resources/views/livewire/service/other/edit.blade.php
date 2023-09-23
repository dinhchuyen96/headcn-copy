<div>
    <div class="page-heading">
        <h1 class="page-title">Sửa dịch vụ khác</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fa fa-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Sửa dịch vụ khác</li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div wire:loading class="loader"></div>
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thông tin dịch vụ</div>
            </div>
            <div class="ibox-body">

                <div class="form-group row">
                    <label for="customer" class="col-2 col-form-label ">Khách hàng <span class="text-danger">
                            *</span></label>
                    <div class="col-4">
                        <select id="customer" name="customer" {{ $disabled_customer ? 'disabled' : '' }}
                            wire:model.lazy="customer" class="custom-select select2-box form-control">
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
                    <label for="technicalId" class="col-2 col-form-label ">NV kĩ thuật <span class="text-danger">
                            *</span></label>
                    <div class="col-4">
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
                </div>


                <div class="table-responsive">
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
                        <button wire:click.prevent="update" type="button" {{ $disabled ? 'disabled' : '' }}
                            class="btn btn-primary">Lưu thông tin</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#customer').on('change', function(e) {
            var data = $('#customer').select2("val");
            @this.set('customer', data);
        });
        $('#fixerId').on('change', function(e) {
            var data = $('#fixerId').select2("val");
            @this.set('fixerId', data);
        });

    });

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
</script>
