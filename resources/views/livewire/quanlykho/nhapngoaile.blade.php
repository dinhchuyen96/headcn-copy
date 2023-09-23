@php
use App\Enum\ReasonChangeInput;
@endphp
<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Nhập ngoại lệ</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="warehouses" class="col-1 col-form-label ">Kho nhập<span class="text-danger">*</span></label>
                        <div class="col-3">
                            <select wire:model="warehouses" id="warehouses" class="custom-select select2-box">
                                <option hidden value="">Chọn Kho</option>
                                @foreach ($warehouseList as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            @error('warehouses_name')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <label for="position_in_warehouse" class="col-1 col-form-label ">Vị trí kho</label>
                        <div class="col-3">
                            <select wire:model="position_in_warehouse" id="position_in_warehouse"
                                class="custom-select select2-box">
                                <option hidden value="">Chọn vị trí</option>
                                @foreach ($positionList as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            @error('position_in_warehouse_name')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <label for="reason" class="col-1 col-form-label ">Lý do<span class="text-danger">*</span></label>
                        <div class="col-3">
                            <select wire:model="reason" id="reason" class="custom-select select2-box">
                                <option hidden value="0">Chọn lý do</option>
                                @foreach ($reasonList as $key => $item)
                                    <option value="{{ $item['value'] }}">{{ $item['text'] }}</option>
                                @endforeach
                            </select>
                            @error('reason')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        
                        <label for="description" class="col-1 col-form-label ">Diễn giải</label>
                        <div class="col-3">
                            <input id="description" wire:model="description" type="text" class="form-control">
                        </div>
                        <label for="accessory_code" class="col-1 col-form-label">Mã phụ tùng<span class="text-danger">*</span></label>
                        <div class="col-3">
                            <input id="accessory_code" wire:model="accessory_code" type="text" class="form-control">
                            @error('accessory_code')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <label for="accessory_name" class="col-1 col-form-label">Tên phụ tùng</label>
                        <div class="col-3">
                            <input id="accessory_name" readonly wire:model="accessory_name" type="text"
                                class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="accessory_quantity" class="col-1 col-form-label">Số lượng<span class="text-danger">*</span></label>
                        <div class="col-3">
                            <input id="accessory_quantity" wire:model="accessory_quantity" type="number"
                                onkeypress="return onlyNumberKey(event)" class="form-control">
                            @error('accessory_quantity')
                            <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row text-right btn-group-mt">
                        <div class="col-md-12">
                            <button type="button" wire:click.prevent="addAccessory({{ $i }})"
                                class="btn btn-primary add-new"><i class="fa fa-plus"></i> Thêm mới</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <div class="table-wrapper">
                            <div id='table_input'>
                                <table class="table table-striped table-bordered readonly_input">
                                    <thead>
                                        <tr>
                                            <th>Vị trí</th>
                                            <th>Mã phụ tùng</th>
                                            <th>Tên phụ tùng</th>
                                            <th>Số lượng</th>
                                            <th>Lý do</th>
                                            <th style="width:50px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($accessories as $key => $value)
                                            <tr>
                                                <td>
                                                    {{ $value['warehouses_name'] . '-' . $value['position_in_warehouse_name'] }}
                                                </td>
                                                <td>
                                                    {{ $value['accessory_code'] }}
                                                </td>
                                                <td>
                                                    {{ $value['accessory_name'] }}
                                                </td>
                                                <td>
                                                    {{ $value['accessory_quantity'] }}
                                                </td>
                                                <td>
                                                    {{ ReasonChangeInput::getDescription($value['reason']) }}
                                                </td>
                                                <td class="align-middle text-center">
                                                    <a class="delete"
                                                        wire:click.prevent="removeAccessory({{ $key }})"
                                                        data-toggle="tooltip" data-original-title="Xóa">
                                                        <i class="fa fa-remove"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row justify-content-center">
                        <button type="button" class="btn btn-primary" wire:click.prevent="store()"><i
                                class="fa fa-plus"></i> Lưu lại</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('js')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            $('#warehouses').on('change', function(e) {
                var data = $('#warehouses').select2("val");
                @this.set('warehouses', data);
                window.livewire.emit('changeWarehouses', data)
            });
            $('#position_in_warehouse').on('change', function(e) {
                var data = $('#position_in_warehouse').select2("val");
                @this.set('position_in_warehouse', data);
                window.livewire.emit('changePosition', data)
            });
            $('#reason').on('change', function(e) {
                var data = $('#reason').select2("val");
                @this.set('reason', data);
            });
            $('#accessory_code').on('change', function(e) {
                let data = e.target.value;
                @this.set('accessory_code', data);
                window.livewire.emit('changeAccessoryCode', data)
            });
        })
    </script>
@endsection
