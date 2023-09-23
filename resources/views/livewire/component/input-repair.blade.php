<div class="table-responsive">
    <div class="table-wrapper">
        <div class="table-title">
            <div class="row">
                <div class="col-sm-8">
                    <h2 class="h2-sec-ttl">Danh sách công việc sửa chữa </h2>
                </div>
                <div class="col-sm-4 text-right">
                    @if (!$isShow)
                        <button {{ $isAddMode || $isEditMode ? 'disabled' : '' }} type="button" wire:click="addNew()"
                            class="btn btn-primary add-new-congviec"><i class="fa fa-plus"></i> Thêm
                            mới</button>
                    @endif
                </div>
            </div>
        </div>
        <div class="responsive">
            <table id="congviec" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width: 200px">Nội dung công việc</th>
                        <th style="width: 200px">Nhân viên sửa chữa</th>
                        <th style="width: 100px">
                            Chi tiền</th>
                        <th style="width: 150px">
                            Đơn vị gia công</th>
                        <th style="width: 150px">Tiền công(VND)</th>
                        <th style="width: 150px">Khuyến mãi(%)</th>
                        <th style="width: 150px">Thành tiền(VND)</th>
                        @if (!$isShow)
                            <th>Thao tác</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($repairTasks as $item)
                        <tr wire:key="{{ $item->id }}">
                            <td>
                                <input type="text" class="form-control" value="{{ $item->workContent }}" readonly
                                    @if ($itemEditID == $item->id) hidden @endif>
                                <select
                                    class="contentEdit form-control select2-box{{ $itemEditID != $item->id ? '-hidden' : '' }}"
                                    wire:model="contentEdit" id="{{ 'contentEdit' . $item->id }}"
                                    @if ($itemEditID != $item->id) hidden @endif>
                                    <option hidden value="">Chọn ND công việc</option>
                                    @foreach ($listContent as $value)
                                        <option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('contentEdit')
                                    @include('layouts.partials.text._error')
                                @enderror
                            </td>
                            <td>
                                <input readonly type="text" class="form-control" value="{{ $item->fixerName }}"
                                    @if ($itemEditID == $item->id) hidden @endif>
                                <select
                                    class="mainFixerIdEdit form-control select2-box{{ $itemEditID != $item->id ? '-hidden' : '' }}"
                                    wire:model="mainFixerIdEdit" id="{{ 'mainFixerIdEdit' . $item->id }}"
                                    @if ($itemEditID != $item->id) hidden @endif>
                                    <option hidden value="">Nhân viên chính</option>
                                    @foreach ($listFixer as $value)
                                        <option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('mainFixerIdEdit')
                                    @include('layouts.partials.text._error')
                                @enderror
                            </td>
                            <td>
                                @if ($item->isOutWork)
                                    <input type="text" class="form-control" onkeypress="return onlyNumberKey(event)"
                                        value="{{ $item->payment }}" readonly
                                        @if ($itemEditID == $item->id) hidden @endif />
                                @endif
                                @if ($isOutWorkRepairEdit)
                                    <input type="number" class="form-control" onkeypress="return onlyNumberKey(event)"
                                        wire:model="paymentEdit" @if ($itemEditID != $item->id) hidden @endif />
                                    @error('paymentEdit')
                                        @include('layouts.partials.text._error')
                                    @enderror
                                @endif
                            </td>
                            <td>
                                @if ($item->isOutWork)
                                    <input type="text" class="form-control" value="{{ $item->processCompany }}"
                                        readonly @if ($itemEditID == $item->id) hidden @endif />
                                @endif
                                @if ($isOutWorkRepairEdit)
                                    <input type="text" class="form-control" wire:model="processCompanyEdit"
                                        @if ($itemEditID != $item->id) hidden @endif />
                                    @error('processCompanyEdit')
                                        @include('layouts.partials.text._error')
                                    @enderror
                                @endif
                            </td>
                            <td>
                                <input type="text" class="form-control" onkeypress="return onlyNumberKey(event)"
                                    value="{{ $item->price }}" readonly
                                    @if ($itemEditID == $item->id) hidden @endif />
                                <input type="number" class="form-control" onkeypress="return onlyNumberKey(event)"
                                    wire:model="priceEdit" @if ($itemEditID != $item->id) hidden @endif />
                                @error('priceEdit')
                                    @include('layouts.partials.text._error')
                                @enderror
                            </td>
                            <td>
                                <input type="text" class="form-control" onkeypress="return onlyNumberKey(event)"
                                    value="{{ $item->promotion }}" readonly
                                    @if ($itemEditID == $item->id) hidden @endif />
                                <input type="number" class="form-control" onkeypress="return onlyNumberKey(event)"
                                    wire:model="promotionEdit" @if ($itemEditID != $item->id) hidden @endif />
                                @error('promotionEdit')
                                    @include('layouts.partials.text._error')
                                @enderror
                            </td>
                            <td>
                                <input type="text" class="form-control" readonly value="{{ $item->totalPrice }}"
                                    @if ($itemEditID == $item->id) hidden @endif />
                                <input type="number" readonly class="form-control" value="{{ $totalPriceEdit }}"
                                    @if ($itemEditID != $item->id) hidden @endif />
                            </td>
                            @if (!$isShow)
                                <td>
                                    <button class="edit border-0"
                                        @if ($itemEditID == $item->id) style="display:none" @else style="display:inline" @endif
                                        data-original-title="Sửa" wire:click="editItem({{ $item->id }})"><i
                                            class="fa fa-edit"></i></button>
                                    <button @if ($itemEditID == $item->id) style="display:none" @endif
                                        class="delete border-0" data-original-title="Xóa"
                                        wire:click="delete({{ $item->id }})"><i class="fa fa-trash"></i>
                                    </button>

                                    <button class="add border-0" data-toggle="tooltip"
                                        @if ($itemEditID != $item->id) style="display:none" @else style="display:inline" @endif
                                        wire:click="updateItem({{ $item->id }})">
                                        <i class="fa fa-check"></i>
                                    </button>
                                    <button @if ($itemEditID != $item->id) style="display:none" @endif
                                        class="delete border-0" data-original-title="Hủy" wire:click="cancel">
                                        <i class="fa fa-remove"></i>
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        @if (!$isAddMode)
                            <tr>
                                <td colspan="{{ $isShow ? 7 : 8 }}" class="text-center text-danger">Chưa có dữ liệu
                                </td>
                            </tr>
                        @endif
                    @endforelse
                    <tr class="{{ $isAddMode ? '' : 'd-none' }}">
                        <td>
                            <select id="content" name="content" wire:model="content"
                                class="custom-select select2-box form-control">
                                <option value="">--Chọn--</option>
                                @foreach ($listContent as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('content')
                                @include('layouts.partials.text._error')
                            @enderror
                        </td>
                        <td>
                            <select id="mainFixerId" name="mainFixerId" wire:model="mainFixerId"
                                class="custom-select select2-box form-control">
                                <option value="">--Chọn--</option>
                                @foreach ($listFixer as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mainFixerId')
                                @include('layouts.partials.text._error')
                            @enderror
                        </td>
                        <td>
                            @if ($isOutWorkRepair)
                                <input type="number" class="form-control" onkeypress="return onlyNumberKey(event)"
                                    wire:model="payment" id="payment">
                                @error('payment')
                                    @include('layouts.partials.text._error')
                                @enderror
                            @else
                                <div></div>
                            @endif
                        </td>
                        <td>
                            @if ($isOutWorkRepair)
                                <input type="te xt" class="form-control" wire:model="processCompany"
                                    id="processCompany">
                                @error('processCompany')
                                    @include('layouts.partials.text._error')
                                @enderror
                            @else
                                <div></div>
                            @endif
                        </td>
                        <td>
                            <input type="number" class="form-control" onkeypress="return onlyNumberKey(event)"
                                wire:model="price" id="price">
                            @error('price')
                                @include('layouts.partials.text._error')
                            @enderror
                        </td>
                        <td>
                            <input type="number" class="form-control" min="0" onkeypress="return onlyNumberKey(event)"
                                max="100" wire:model="promotion" id="promotion">
                            @error('promotion')
                                @include('layouts.partials.text._error')
                            @enderror
                        </td>
                        <td>
                            <input type="number" readonly class="form-control format_number"
                                wire:model.defer="totalPrice" id="totalPrice">
                        </td>
                        <td><a class="add" data-toggle="tooltip" style="display: inline;"
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
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#mainFixerId').on('change', function(e) {
            var data = $('#mainFixerId').select2("val");
            @this.set('mainFixerId', data);
        });
        $('.mainFixerIdEdit').on('change', function(e) {
            let idElement = '#' + event.target.id.split("-")[1];
            var data = $(idElement).select2("val");
            @this.set('mainFixerIdEdit', data);
        });
        $('#content').on('change', function(e) {
            var data = $('#content').select2("val");
            @this.set('content', data);
        });
        $('.contentEdit').on('change', function(e) {
            let idElement = '#' + event.target.id.split("-")[1];
            var data = $(idElement).select2("val");
            @this.set('contentEdit', data);
        });
    });
</script>
