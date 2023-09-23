<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">Thông tin nhập xe</div>
        </div>
        <div class="ibox-body">
            {{-- <div class="row justify-content-end">
                <div class="col-md-1 "><button type="button" class="btn btn-info add-new" data-toggle="modal"
                        data-target="#modal-form-import"><i class="fa fa-upload"></i> IMPORT FILE </button></div>
            </div> --}}
            <form>
                <div class="form-group row mt-1">
                    <label for="SupplierCode" class="col-1 col-form-label">Mã nhà CC <span
                            class="text-danger">*</span></label>
                    <div class="col-3">
                        <input id="SupplierCode" name="SupplierCode"
                            wire:model.lazy='supplierCode' placeholder="Mã nhà cung cấp" type="text"
                            class="form-control form-red" required="required" {{ $isViewMode ? 'disabled' : '' }}>
                        @error('supplierCode')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                    <label for="SupplierName" class="col-1 col-form-label">Nhà cung cấp <span
                            class="text-danger">*</span></label>
                    <div class="col-3">
                        <input id="SupplierName" name="SupplierName" wire:model.lazy='name'
                            placeholder="Tên nhà cung cấp" type="text" class="form-control" required="required"
                            {{ $isViewMode ? 'disabled' : '' }}>
                        @error('name')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                    <label for="PhoneNumber" class="col-1 col-form-label">Số điện thoại <span
                            class="text-danger">*</span></label>
                    <div class="col-3">
                        <input id="PhoneNumber" name="PhoneNumber" wire:model.lazy='phone' placeholder="Số điện thoại"
                            type="number" class="form-control" {{ $isViewMode ? 'disabled' : '' }}>
                        @error('phone')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                </div>

                <div class="form-group row">

                    <label for="Email" class="col-1 col-form-label">Email</label>
                    <div class="col-3">
                        <input id="Email" name="Email" wire:model.lazy='email' placeholder="Email" type="text"
                            class="form-control" {{ $isViewMode ? 'disabled' : '' }}>
                        @error('email')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                </div>
                @livewire('component.address')
                <div class="form-group row">
                    <label class="col-1 col-form-label">Kho<span class="text-danger"> *</span></label>
                    <div class="col-3">
                        <select wire:model="warehouse" id="warehouse"
                            {{ $isViewMode || $isEditMode ? 'disabled' : '' }} class="custom-select select2-box">
                            <option value="">Chọn Kho</option>
                            @foreach ($warehouseList as $key => $item)
                                <option value="{{ $key }}">
                                    {{ $item }}</option>
                            @endforeach
                        </select>
                        @error('warehouse')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>

                    <label for="BarCode" class="col-md-1 col-form-label">Barcode</label>
                    <div class="col-md-3">
                        <input id="BarCode" wire:model="barCode" placeholder="Barcode xe" type="text"
                            class="form-control" {{ $isViewMode ? 'disabled' : '' }}>
                        @error('barCode')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 text-left">
                        <button {{ $isViewMode ? 'disabled' : '' }} type="button"
                        wire:click="addBarCode('{{ $barCode }}')" class="btn btn-info add-new">
                            <i class="fa fa-search"></i> SCAN
                        </button>
                    </div>
                </div>

            </form>
            <div class="table-responsive">
                <div class="table-wrapper" style="padding: 20px 0px">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-6">
                                <h5 class="ibox-title font-weight-bold text-primary">Danh sách xe nhập</h5>
                            </div>
                            <div class="col-sm-6 text-right">
                                <input type='checkbox' id='check_hvn_plan' class="ml-3"
                                    wire:model="check_hvn_plan" {{$check_hvn_plan ?'checked':''}}
                                    > Xe trong kế hoạch nhập của HONDA
                                <button type="button" {{ $isViewMode ? 'hidden' : '' }} wire:click="add()"
                                    class="btn btn-primary mr-3" @if (!$addBtn) disabled @endif><i class="fa fa-plus"></i>
                                    Thêm
                                    mới</button>
                                <button type="button" {{ $isViewMode||$isEditMode ? 'hidden' : '' }} class="btn btn-info mr-3" data-toggle="modal"
                                    data-target="#modal-form-import"><i class="fa fa-upload" aria-hidden="true"></i>
                                    IMPORT FILE
                                </button>
                                <button type="button" {{ $isViewMode||$isEditMode ? 'hidden' : '' }} class="btn btn-info" data-toggle="modal"
                                    data-target="#modal-form-import-cnbh"><i class="fa fa-upload" aria-hidden="true"></i>
                                    IMPORT CNBH
                                </button>
                            </div>
                        </div>
                    </div>
                    @livewire('component.list-input-motorbike', ['type'=>3])
                </div>
            </div>
            <div class="form-group row">
                <div class="col-12 text-center">
                    <button name="submit" {{ $isViewMode ? 'hidden' : '' }} type="submit" wire:click='store'
                        class="btn btn-primary">
                        @if ($order_id)
                            Cập nhật hóa đơn nhập hàng
                        @else
                            Tạo hóa đơn nhập hàng
                        @endif

                    </button>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.partials.button._importForm')
    @include('layouts.partials.button._importCNBHForm')
</div>
<script>
    window.livewire.on('close-modal-import', () => {
        document.getElementById('modal-form-import').click();
    });
    window.livewire.on('close-modal-import-cnbh', () => {
        document.getElementById('modal-form-import-cnbh').click();
    });
    document.addEventListener('livewire:load', function() {
        $(function() {
            $("#BarCode").on('keyup', function(e) {
                if (e.key === 'Enter' || e.keyCode === 13) {
                    window.livewire.emit('addBarCode', document.getElementById('BarCode')
                        .value);
                }

            });
        });
    });
    window.livewire.on('resetInputBarCode', () => {
        document.getElementById('BarCode').value = '';
    });
    document.addEventListener('DOMContentLoaded', function() {
        $('#warehouse').on('change', function(e) {
            var data = $('#warehouse').select2("val");
            @this.set('warehouse', data);
        });
    })
</script>
