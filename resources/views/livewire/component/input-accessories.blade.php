<div>
    <div class="table-title">
        <div class="row">
            <div class="col-sm-8">
                <h2 class="h2-sec-ttl">Danh sách phụ tùng thay thế</h2>
            </div>
            <div class="col-sm-4 text-right">
                @if (!$isShow)
                    <button type="button" {{ $isAddAccessory ? 'disabled' : '' }} wire:click="addNew()"
                        class="btn btn-primary"><i class="fa fa-plus"></i>
                        Thêm
                        mới</button>
                @endif
            </div>
        </div>
    </div>
    <div class="responsive">
        <table class="table table-striped table-bordered readonly_input">
            <thead>
                <tr>
                    <th style="width: 200px">Mã PT</th>
                    <th style="width: 200px">Vị trí kho</th>
                    <th style="width: 200px">Tên PT</th>
                    {{-- <th style="width: 200px">Mã NCC</th> --}}
                    <th style="width: 150px">Số lượng</th>
                    <th style="width: 150px">Đơn giá</th>
                    <th style="width: 150px">Khuyến mãi(%)</th>
                    <th style="width: 150px">Thành tiền</th>
                    {{-- <th style="width: 150px"> Giá in hóa đơn</th>
                    <th style="width: 150px">Giá thực tế</th> --}}
                    <th style="width: 150px">Không thay thế</th>
                    @if (!$isShow)
                        <th style="width:100px">Thao tác</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <div wire:loading class="loader"></div>
                @forelse($accessoryDraft as $item)
                    <tr wire:key="{{ $item->id }}">
                        <td>
                            <input readonly {{ $status ? 'disabled' : '' }} type="text" class="form-control"
                                value="{{ $item->code }}" @if ($itemEditID == $item->id) hidden @endif>
                            <select {{ $status ? 'disabled' : '' }}
                                class="accessaryNumberEdit form-control select2-box{{ $itemEditID != $item->id ? '-hidden' : '' }}"
                                wire:model="accessaryNumberEdit" id="{{ 'accessaryNumberEdit' . $item->id }}"
                                @if ($itemEditID != $item->id) hidden @endif>
                                <option hidden value="">Chọn Mã phụ tùng</option>
                                @foreach ($accessory as $value)
                                    <option value="{{ $value }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('accessaryNumberEdit')
                                @include('layouts.partials.text._error')
                            @enderror
                        </td>
                        <td>
                            <input readonly {{ $status ? 'disabled' : '' }} type="text" class="form-control"
                                value="{{ $item->accessorie->positionInWarehouse->warehouse->name . '-' . $item->accessorie->positionInWarehouse->name }}"
                                @if ($itemEditID == $item->id) hidden @endif>
                            <select {{ $status ? 'disabled' : '' }}
                                class="positionWarehouseEdit form-control select2-box{{ $itemEditID != $item->id ? '-hidden' : '' }}"
                                wire:model="positionWarehouseEdit" id="{{ 'positionWarehouseEdit' . $item->id }}"
                                @if ($itemEditID != $item->id) hidden @endif>
                                <option hidden value="">Chọn vị trí kho</option>
                                @foreach ($positionWarehouseList as $value)
                                    <option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
                                @endforeach
                            </select>
                            @error('positionWarehouseEdit')
                                @include('layouts.partials.text._error')
                            @enderror
                        </td>
                        <td>
                            <input type="text" class="form-control" value="{{ $item->accessorie->name ?? '' }}"
                                readonly @if ($itemEditID == $item->id) hidden @endif>
                            <input type="text" class="form-control" readonly wire:model.lazy="accessaryNameEdit"
                                @if ($itemEditID != $item->id) hidden @endif>
                        </td>
                        {{-- <td>
                            <input {{ $status ? 'disabled' : '' }} type="text" class="form-control"
                                value="{{ $item->accessorie->supplier->code ?? '' }}" readonly
                                @if ($itemEditID == $item->id) hidden @endif>
                            <input {{ $status ? 'disabled' : '' }} type="text" class="form-control" readonly
                                wire:model.lazy="supplierEdit" @if ($itemEditID != $item->id) hidden @endif>
                        </td> --}}

                        <td>

                            <input type="text" class="form-control"
                                value="{{ number_format($item->quantity ?? '') }}" readonly
                                @if ($itemEditID == $item->id) hidden @endif>
                            <input type="text" class="form-control" wire:model.lazy="quantityEdit"
                                @if ($itemEditID != $item->id) hidden @endif
                                onkeypress="return onlyNumberKey(event)">
                            @if ($accessaryNumberEdit && $positionWarehouseEdit && $availableQuantityEdit >= 0 && $itemEditID == $item->id)
                                <p class="text-info">Còn lại :{{ $availableQuantityEdit }}</p>
                            @elseif($accessaryNumberEdit && $positionWarehouseEdit && $availableQuantityEdit < 0 && $itemEditID == $item->id)
                                <p class="text-info">Không đủ bán</p>
                            @endif
                            <div @if ($itemEditID != $item->id) hidden @endif>
                                @error('quantityEdit')
                                    @include('layouts.partials.text._error')
                                @enderror
                            </div>
                        </td>
                        <td>
                            <input type="text" class="form-control" value="{{ number_format($item->price ?? '') }}"
                                @if ($itemEditID == $item->id) hidden @endif readonly>

                            <input {{ $status ? 'disabled' : '' }} type="text" class="form-control"
                                wire:model.lazy="priceEdit" @if ($itemEditID != $item->id) hidden @endif
                                onkeypress="return onlyNumberKey(event)">
                        </td>

                        <td><input {{ $status ? 'disabled' : '' }} type="text" class="form-control"
                                value="{{ number_format($item->promotion ?? '') }}" readonly
                                @if ($itemEditID == $item->id) hidden @endif>
                            <input {{ $status ? 'disabled' : '' }} type="text" class="form-control"
                                wire:model.lazy="promotionEdit" @if ($itemEditID != $item->id) hidden @endif
                                onkeypress="return onlyNumberKey(event)">
                            @error('promotionEdit')
                                @include('layouts.partials.text._error')
                            @enderror
                        </td>


                        <td><input {{ $status ? 'disabled' : '' }} type="text" class="form-control"
                                @if ($itemEditID == $item->id) hidden @endif
                                value="{{ number_format(round(($item->price * $item->quantity * (100 - $item->promotion)) / 100)) }}"
                                readonly>
                            <input {{ $status ? 'disabled' : '' }} type="text" class="form-control"
                                wire:model.lazy="totalEdit" @if ($itemEditID != $item->id) hidden @endif readonly
                                onkeypress="return onlyNumberKey(event)">
                        </td>
                        {{-- <td>
                            <input {{ $status ? 'disabled' : '' }} type="text" class="form-control"
                                @if ($itemEditID == $item->id) hidden @endif
                                value="{{ number_format($item->vat_price ?? '') }}" readonly
                                onkeypress="return onlyNumberKey(event)">
                            <input {{ $status ? 'disabled' : '' }} type="text" class="form-control format_number"
                                wire:model.lazy="vat_priceEdit" @if ($itemEditID != $item->id) hidden @endif
                                onkeypress="return onlyNumberKey(event)">
                        </td>
                        <td>
                            <input {{ $status ? 'disabled' : '' }} type="text" class="form-control"
                                @if ($itemEditID == $item->id) hidden @endif
                                value="{{ number_format($item->actual_price ?? '') }}" readonly onkeypress="return
                        onlyNumberKey(event)">
                            <input {{ $status ? 'disabled' : '' }} type="text" class="form-control format_number"
                                wire:model.lazy="actual_priceEdit" @if ($itemEditID != $item->id) hidden @endif
                                onkeypress="return onlyNumberKey(event)">
                        </td> --}}
                        <td>
                            <input {{ $status ? 'disabled' : '' }} style="margin:10px" type="checkbox"
                                @if ($itemEditID == $item->id) hidden @endif
                                {{ $item->is_atrophy == 1 ? 'checked' : '' }} disabled>
                            <input {{ $status ? 'disabled' : '' }} style="margin:10px" type="checkbox"
                                wire:model.lazy="isAtrophyEdit" @if ($itemEditID != $item->id) hidden @endif>
                        </td>
                        @if (!$isShow)
                            <td>
                                <button {{ $status ? 'disabled' : '' }} class="edit border-0"
                                    @if ($itemEditID == $item->id) style="display:none"
                            @else style="display:inline" @endif
                                    data-original-title="Sửa" wire:click="editItem({{ $item->id }})"><i
                                        class="fa fa-edit"></i></button>
                                <button {{ $status ? 'disabled' : '' }}
                                    @if ($itemEditID == $item->id) style="display:none" @endif
                                    class="delete border-0" data-original-title="Xóa"
                                    wire:click="delete({{ $item->id }})"><i class="fa fa-trash"></i>
                                </button>

                                <button {{ $status ? 'disabled' : '' }} class="add border-0" data-toggle="tooltip"
                                    @if ($itemEditID != $item->id) style="display:none"
                            @else style="display:inline" @endif
                                    wire:click="updateItem({{ $item->id }})">
                                    <i class="fa fa-check"></i>
                                </button>
                                <button {{ $status ? 'disabled' : '' }}
                                    @if ($itemEditID != $item->id) style="display:none" @endif
                                    class="delete border-0" data-original-title="Hủy" wire:click="cancel"><i
                                        class="fa fa-remove"></i></button>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr class="{{ $isAddAccessory ? 'd-none' : '' }}">
                        @if (!$isShow)
                            <td colspan="@if ($type == 3) 6 @elseif($type == 1)7 @else 9 @endif"
                                class="text-center text-danger"> Chưa có dữ liệu</td>
                        @else
                            <td colspan="@if ($type == 3) 6 @elseif($type == 1)7 @else 8 @endif"
                                class="text-center text-danger"> Chưa có dữ liệu</td>
                        @endif
                    </tr>
                @endforelse
                <tr class="{{ $isAddAccessory ? '' : 'd-none' }}">
                    <td>
                        <select name="accessaryNumber" {{ $status ? 'disabled' : '' }}
                            class="form-control select2-box" wire:model="accessaryNumber" id="accessaryNumber">
                            <option value="">Mã phụ tùng</option>
                            @foreach ($accessory as $value)
                                <option value="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('accessaryNumber')
                            @include('layouts.partials.text._error')
                        @enderror
                    </td>
                    <td>
                        <select name="positionWarehouse" {{ $status ? 'disabled' : '' }}
                            class="form-control select2-box" wire:model="positionWarehouse" id="positionWarehouse">
                            <option value="">Vị trí kho</option>
                            @foreach ($positionWarehouseList as $value)
                                <option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
                            @endforeach
                        </select>
                        @error('positionWarehouse')
                            @include('layouts.partials.text._error')
                        @enderror
                    </td>
                    <td>
                        <input readonly type="text" class="form-control" wire:model.lazy="accessaryName">
                    </td>
                    {{-- <td><input readonly type="text" class="form-control" wire:model.lazy="supplier"></td> --}}
                    <td>
                        <input type="text" class="form-control" wire:model.lazy="quantity"
                            onkeypress="return onlyNumberKey(event)">
                        @if ($accessaryNumber && $positionWarehouse && $availableQuantity >= 0)
                            <p class="text-info">Còn lại :
                                {{ $availableQuantity }}</p>
                        @elseif($accessaryNumber && $positionWarehouse && $availableQuantity < 0)
                            <p class="text-info">Không đủ bán</p>
                        @endif
                        @error('quantity')
                            @include('layouts.partials.text._error')
                        @enderror
                    </td>
                    <td>
                        <input type="text" class="form-control format_number" wire:model.lazy="price">
                    </td>
                    <td>
                        <input type="text" class="form-control" wire:model.lazy="promotion"
                            onkeypress="return onlyNumberKey(event)">
                        @error('promotion')
                            @include('layouts.partials.text._error')
                        @enderror
                    </td>
                    <td>
                        <input type="text" class="form-control format_number" wire:model="total" readonly>
                    </td>
                    {{-- <td><input type="text" class="form-control format_number" wire:model.lazy="vat_price"
                            onkeypress="return onlyNumberKey(event)"></td>
                    <td><input type="text" class="form-control format_number" wire:model.lazy="actual_price"
                            onkeypress="return onlyNumberKey(event)"></td> --}}
                    <td>
                        <input style="margin:10px" type="checkbox" wire:model="isAtrophy">
                    </td>

                    <td>
                        <a class="add" data-toggle="tooltip" style="display: inline;"
                            data-original-title="Thêm" wire:click="addItem()">
                            <i class="fa fa-plus"></i></a>
                        <a wire:click="cancelNew()" class="delete" data-toggle="tooltip"
                            data-original-title="Xóa">
                            <i class="fa fa-remove"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#accessaryNumber').on('change', function(e) {
                var data = $('#accessaryNumber').select2("val");
                @this.set('accessaryNumber', data);
            });
            $('#positionWarehouse').on('change', function(e) {
                var data = $('#positionWarehouse').select2("val");
                console.log(data);
                @this.set('positionWarehouse', data);
            });
            $('.accessaryNumberEdit').on('change', function(e) {
                let idElement = '#' + event.target.id.split("-")[1];
                var data = $(idElement).select2("val");
                console.log(data);
                @this.set('accessaryNumberEdit', data);
            });
            $('.positionWarehouseEdit').on('change', function(e) {
                let idElement = '#' + event.target.id.split("-")[1];
                var data = $(idElement).select2("val");
                console.log(data);
                @this.set('positionWarehouseEdit', data);
            });
        });


        // document.addEventListener('setEventForSelectWhenEdit', function() {
        //     $('#accessaryNumberEdit').on('change', function(e) {
        //         var data = $('#accessaryNumberEdit').select2("val");
        //         @this.set('accessaryNumberEdit', data);
        //     });
        //     $('#positionWarehouseEdit').on('change', function(e) {
        //         var data = $('#positionWarehouseEdit').select2("val");
        //         @this.set('positionWarehouseEdit', data);
        //     });
        // });
    </script>
</div>
