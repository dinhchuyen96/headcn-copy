<div id='table_input'>
    <style>
        table .form-control {
            font-size: 0.85rem;
        }

        table tbody {
            font-size: 0.85rem;
        }

        #select2-chassic-results>li,
        #select2-engine-results>li,
        #select2-chassicEdit-results>li,
        #select2-engineEdit-results>li {
            font-size: 0.85rem;
        }

    </style>
    <input type="hidden" id="count_accessories" value="{{ $data ? $data->count() : 0 }}">
    <table class="table table-striped table-bordered readonly_input">
        <thead>
            <tr>
                <th style="width:175px">Số khung</th>
                <th style="width:145px">Số máy</th>
                <th>Đời xe</th>
                <th>Danh mục</th>
                <th>Phân loại</th>
                <th>Màu xe</th>
                <th style="width:115px">Giá NY</th>
                @if (in_array($type, [1, 2]))
                    <th style="width:115px">Giá in HĐ</th>
                    <th style="width:115px">Giá thực tế</th>
                @endif
                @if ($type == 3)
                    <th style="width:137px">Ngày nhập</th>
                @endif
                <th style="width:100px">Trạng thái</th>
                <th style="width:100px" {{ $status ? 'hidden' : '' }}>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $key => $item)
                <tr>
                    <td>
                        @if ($itemEditID != $item->id)
                            <input type="text" class="form-control" value="{{ $item->chassic_no }}" readonly>
                        @else
                            @if ($item->status == 1)
                                <input type="text" class="form-control" id="chassicEdit1" wire:model.lazy="chassicEdit"
                                    disabled>
                            @else
                                @if ($isHVN)
                                    <select wire:model="chassicEdit" id="chassicEdit" class="custom-select select2-box"
                                        {{ $item->status ? 'disabled' : '' }}>
                                        <option value="">Chọn số khung</option>
                                        @foreach ($chassicEditList as $val)
                                            <option value="{{ $val }}">
                                                {{ $val }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" class="form-control" wire:model.lazy="chassicEdit"
                                        id="chassicEdit2">
                                @endif
                            @endif
                            @error('chassicEdit')
                                @include('layouts.partials.text._error')
                            @enderror

                        @endif
                    </td>
                    <td>
                        @if ($itemEditID != $item->id)
                            <input type="text" class="form-control" value="{{ $item->engine_no }}" readonly>
                        @else
                            @if ($item->status == 1)
                                <input type="text" class="form-control" id="engineEdit1" wire:model.lazy="engineEdit"
                                    disabled>
                            @else
                                @if ($isHVN)
                                    <select wire:model.lazy="engineEdit" id="engineEdit"
                                        class="custom-select select2-box" {{ $item->status ? 'disabled' : '' }}>
                                        <option value="">Chọn số máy</option>
                                        @foreach ($engineEditList as $val)
                                            <option value="{{ $val }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" class="form-control" wire:model="engineEdit" id="engineEdit2">
                                @endif
                            @endif

                            @if ($itemEditID == $item->id)
                                @error('engineEdit')
                                    @include('layouts.partials.text._error')
                                @enderror
                            @endif
                        @endif
                    </td>
                    <td>
                        <input type="text" class="form-control" value="{{ $item->model_code }}" readonly
                            @if ($itemEditID == $item->id) hidden @endif>
                        <input type="text" class="form-control" wire:model.lazy="modelEdit"
                            @if ($itemEditID != $item->id) hidden @endif {{ $isHVN ? 'readonly' : '' }}>
                        @if ($itemEditID == $item->id)
                            @error('modelEdit')
                                @include('layouts.partials.text._error')
                            @enderror
                        @endif
                    </td>
                    <td>
                        <input type="text" class="form-control" value="{{ $item->model_list }}" readonly
                            @if ($itemEditID == $item->id) hidden @endif>
                        <input type="text" class="form-control" wire:model.lazy="modelListEdit"
                            @if ($itemEditID != $item->id) hidden @endif {{ $isHVN ? 'readonly' : '' }}>
                        @if ($itemEditID == $item->id)
                            @error('modelListEdit')
                                @include('layouts.partials.text._error')
                            @enderror
                        @endif
                    </td>
                    <td>
                        <input type="text" class="form-control" value="{{ $item->model_type }}" readonly
                            @if ($itemEditID == $item->id) hidden @endif>
                        <input type="text" class="form-control" wire:model.lazy="modelTypeEdit"
                            @if ($itemEditID != $item->id) hidden @endif {{ $isHVN ? 'readonly' : '' }}>
                        @if ($itemEditID == $item->id)
                            @error('modelTypeEdit')
                                @include('layouts.partials.text._error')
                            @enderror
                        @endif
                    </td>
                    <td>
                        <input type="text" class="form-control" value="{{ $item->color }}" readonly
                            @if ($itemEditID == $item->id) hidden @endif>
                        <input type="text" class="form-control" wire:model.lazy="colorEdit"
                            @if ($itemEditID != $item->id) hidden @endif {{ $isHVN ? 'readonly' : '' }}>
                        @if ($itemEditID == $item->id)
                            @error('colorEdit')
                                @include('layouts.partials.text._error')
                            @enderror
                        @endif
                    </td>
                    <td>
                        <input type="text" class="form-control format_number"
                            value="{{ number_format($item->price) }}" readonly
                            @if ($itemEditID == $item->id) hidden @endif>
                        <input type="text" class="form-control format_number" wire:model.lazy="priceEdit"
                            @if ($itemEditID != $item->id) hidden @endif {{ $isHVN ? 'readonly' : '' }}>
                        @if ($itemEditID == $item->id)
                            @error('priceEdit')
                                @include('layouts.partials.text._error')
                            @enderror
                        @endif
                    </td>
                    <td @if ($type == 3) hidden @endif>
                        <input type="text" class="form-control" @if ($itemEditID == $item->id) hidden @endif
                            value="{{ number_format($item->vat_price) }}" readonly>
                        <input type="text" class="form-control format_number" wire:model="vat_priceEdit"
                            onkeypress="return onlyNumberKey(event)" @if ($itemEditID != $item->id) hidden @endif>
                        @if ($itemEditID == $item->id)
                            @error('vat_priceEdit')
                                @include('layouts.partials.text._error')
                            @enderror
                        @endif
                    </td>
                    <td @if ($type == 3) hidden @endif>
                        <input type="text" class="form-control" @if ($itemEditID == $item->id) hidden @endif
                            value="{{ number_format($item->actual_price) }}" readonly>
                        <input type="text" class="form-control format_number" wire:model="actual_priceEdit"
                            onkeypress="return onlyNumberKey(event)" @if ($itemEditID != $item->id) hidden @endif>
                        @if ($itemEditID == $item->id)
                            @error('actual_priceEdit')
                                @include('layouts.partials.text._error')
                            @enderror
                        @endif
                    </td>

                    <td @if ($type != 3) hidden @endif
                        @if ($itemEditID != $item->id) hidden @endif>
                        <input type="date" class="form-control input-date-kendo-edit"
                            id="buyDateEdit{{ $key }}" max='{{ date('Y-m-d') }}'
                            wire:model.lazy="buyDateEdit">
                    </td>
                    <td @if ($type != 3) hidden @endif
                        @if ($itemEditID == $item->id) hidden @endif>
                        <input type="text" class="form-control"
                            value="{{ reFormatDate($item->buy_date, 'd/m/Y') }}" readonly>
                    </td>
                    <td>

                        @if ($item->status == 1)
                            <span class="badge badge-primary">Đã lưu </span>
                        @elseif ($item->status == 0)
                            <span class="badge badge-warning">Chờ xử lý </span>
                        @endif
                    </td>
                    <td {{ $status ? 'hidden' : '' }}>
                        <button class="edit border-0"
                            @if ($itemEditID == $item->id) style="display:none" @else style="display:inline" @endif
                            data-original-title="Sửa" wire:click="editItem({{ $item->id }})">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button @if ($itemEditID == $item->id) style="display:none" @endif
                            class="delete border-0 @php echo ($totalMotorbike == 1) ? 'delete-order' : 'delete-order-detail' @endphp"
                            data-original-title="Xóa" data-order-detail-id="{{ $item->id }}"
                            data-order-id="{{ $order_id }}">
                            <i class="fa fa-trash"></i>
                        </button>

                        <button class="add border-0" data-toggle="tooltip"
                            @if ($itemEditID != $item->id) style="display:none" @else style="display:inline" @endif
                            wire:click="updateItem({{ $item->id }})">
                            <i class="fa fa-check"></i>
                        </button>
                        <button @if ($itemEditID != $item->id) style="display:none" @endif class="delete border-0"
                            data-original-title="Hủy" wire:click="cancel"><i class="fa fa-remove"></i></button>
                    </td>
                </tr>

            @empty
                @if (!$addStatus)
                    <tr style="font-size: 14px">
                        <td colspan="@if ($type == 3) 9 @else 10 @endif"
                            class="text-center text-danger" {{ $status ? '' : 'hidden' }}>
                            Chưa có dữ liệu
                        </td>
                        <td colspan="@if ($type == 3) 10 @else 11 @endif"
                            class="text-center text-danger" {{ $status ? 'hidden' : '' }}>
                            Chưa có dữ liệu
                        </td>
                    </tr>
                @endif
            @endforelse
            @if ($addStatus)
                <tr>
                    <td>
                        @if ($isHVN)
                            <select wire:model="chassic" id="chassic" class="custom-select select2-box">
                                <option value="">Chọn số khung</option>
                                @foreach ($chassicList as $val)
                                    <option {{ $val==$chassic ?'selected' : '' }} value="{{ $val }}">{{ $val }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" class="form-control" wire:model="chassic">
                        @endif
                        @error('chassic')
                            @include('layouts.partials.text._error')
                        @enderror
                    </td>
                    <td>
                        @if ($isHVN)
                            <select wire:model="engine" id="engine" class="custom-select select2-box">
                                <option value=""">Chọn số máy</option>
                                     @foreach ($engineList as $val)
                                <option {{ $val == $engine ?'selected' : ''}} value="{{ $val }}">{{ $val }}</option>
                        @endforeach
                        </select>
                    @else
                        <input type="text" class="form-control" wire:model="engine">
            @endif
            @error('engine')
                @include('layouts.partials.text._error')
            @enderror
            </td>
            <td>
                <input type="text" class="form-control" wire:model.lazy="model" id="ModelName1"
                    {{ $isHVN ? 'readonly' : '' }}>
                @error('model')
                    @include('layouts.partials.text._error')
                @enderror
            </td>
            <td>
                <input type="text" class="form-control" wire:model.lazy="modelList" id="ModelListName"
                    {{ $isHVN ? 'readonly' : '' }}>
                @error('modelList')
                    @include('layouts.partials.text._error')
                @enderror
            </td>
            <td>
                <input type="text" class="form-control" wire:model.lazy="modelType" id="ModelTypeName"
                    {{ $isHVN ? 'readonly' : '' }}>
                @error('modelType')
                    @include('layouts.partials.text._error')
                @enderror
            </td>
            <td>
                <input type="text" class="form-control" wire:model.lazy="color" id="Color1"
                    {{ $isHVN ? 'readonly' : '' }}>
                @error('color')
                    @include('layouts.partials.text._error')
                @enderror
            </td>

            <td>
                @if (!$isHVN)
                    <input type="text" class="form-control format_number" wire:model.lazy="price" id="Price1">
                    @error('price')
                        @include('layouts.partials.text._error')
                    @enderror
                @else
                    <div class="pt-2">{{ number_format($price) }}</div>
                    @error('price')
                        @include('layouts.partials.text._error')
                    @enderror
                @endif
            </td>
            @if (in_array($type, [1, 2]))
                <td>
                    <input type="number" class="form-control format_number" wire:model.lazy="vat_price"
                        onkeypress="return onlyNumberKey(event)" id="VatPrice">
                    @error('vat_price')
                        @include('layouts.partials.text._error')
                    @enderror
                </td>

                <td>
                    <input type="number" class="form-control format_number" wire:model.lazy="actual_price"
                        onkeypress="return onlyNumberKey(event)" id="ActualPrice">
                    @error('actual_price')
                        @include('layouts.partials.text._error')
                    @enderror
                </td>
            @endif
            @if ($type == 3)
                <td>
                    <input type="date" class="form-control input-date-kendo-now" wire:model.lazy="buyDate" id="buyDate"
                    max='{{ now()->format('Y-m-d') }}'>
                    @error('buyDate')
                        @include('layouts.partials.text._error')
                    @enderror
                </td>
            @endif
            <td>
                <span class="badge badge-default"> Đang nhập</span>
            </td>
            <td><a class="add" data-toggle="tooltip" style="display: inline;" data-original-title="Thêm"
                    wire:click="addItem()">
                    <i class="fa fa-plus"></i></a>
                <a class="edit" data-toggle="tooltip" style="display: none;" data-original-title="Sửa">
                    <i class="fa fa-edit"></i></a>
                <a class="delete" wire:click="cancelAdd()" data-toggle="tooltip" data-original-title="Xóa">
                    <i class="fa fa-remove"></i></a>
            </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var totalMotorbike = {{ $totalMotorbike }};
        $("#table_input").on("change", "#chassic", function() {
            var data = $('#chassic').select2("val");
            @this.set('chassic', data);
        })
        $("#table_input").on("change", "#engine", function() {
            var data = $('#engine').select2("val");
            @this.set('engine', data);
        })
        $("#table_input").on("change", "#chassicEdit", function() {
            var data = $('#chassicEdit').select2("val");
            @this.set('chassicEdit', data);
        })
        $("#table_input").on("change", "#engineEdit", function() {
            var data = $('#engineEdit').select2("val");
            @this.set('engineEdit', data);
        });
        $(document).on("click", ".delete-order", function() {
            var orderId = $(this).attr('data-order-id');
            var orderDetailId = $(this).attr('data-order-detail-id');
            Swal.fire({
                title: 'Bạn có muốn xóa đơn hàng này?',
                icon: 'question',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy bỏ',
                showCancelButton: true,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('delete', orderDetailId);
                } else {
                }
            })
        });
        $(document).on("click", ".delete-order-detail", function() {
            var orderDetailId = $(this).attr('data-order-detail-id');
            window.livewire.emit('delete', orderDetailId);
        });
    })
</script>
