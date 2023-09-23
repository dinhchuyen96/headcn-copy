<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">Thông tin bán buôn </div>
        </div>
        <div class="ibox-body">
            <form>
                <div wire:loading class="loader"></div>
                <div class="form-group row mt-1">
                    <label for="PhoneNumber" class="col-1 col-form-label">Số điện thoại <span
                            class="text-danger">*</span></label>
                    <div class="col-3">
                        <input id="PhoneNumber" name="PhoneNumber"
                            placeholder="Số điện thoại" type="number" wire:model.lazy='phone' class="form-control form-red"
                            required="required" {{ $status ? 'disabled' : '' }}>
                        @error('phone')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                    <label for="CustomerName" class="col-1 col-form-label">Tên khách hàng<span
                            class="text-danger">*</span></label>
                    <div class="col-3">
                        <input id="CustomerName" name="CustomerName"
                               placeholder="Tên khách hàng" type="text" wire:model.lazy='name' class="form-control form-red"
                               required="required"  {{ $status ? 'disabled' : '' }}>
                        @error('name')
                        @include('layouts.partials.text._error')
                        @enderror
                    </div>
                    <label for="sellerId" class="col-1 col-form-label ">NV bán hàng</label>
                    <div class="col-3">
                        <select id="sellerId" name="sellerId" {{ $status ? 'disabled' : '' }}
                            wire:model.lazy="sellerId" class="custom-select select2-box form-control">
                            <option value="">--Chọn--</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}
                                </option>
                            @endforeach
                        </select>

                    </div>
                </div>
                @livewire('component.address')

                <div class="form-group row">
                    <label for="technicalId" class="col-1 col-form-label ">NV kĩ thuật</label>
                    <div class="col-3">
                        <select id="technicalId" name="technicalId" wire:model.lazy="technicalId" {{ $status ? 'disabled' : '' }}
                            class="custom-select select2-box form-control">
                            <option value="">--Chọn--</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <label for="BarCode" class="col-md-1 col-form-label">Barcode</label>
                    <div class="col-md-3">
                        <input id="BarCode" wire:model.lazy="barCode" placeholder="Barcode xe" type="text"
                            class="form-control" {{ $status ? 'disabled' : '' }}>
                        @error('barCode')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="virtual col-md-1 text-left">
                        <input type='checkbox' {{ $order_id ? 'disabled' : '' }} style="margin: 10px 0px" id='isVirtual' wire:model="isVirtual"> Đơn ảo
                    </div>
                    <div class="text-left col-md-2"> <button {{ $status ? 'disabled' : '' }} type="button"
                            wire:click="addBarCode('{{ $barCode }}')" style="font-size: 16px;" class="btn btn-info add-new "><i
                                class="fa fa-search"></i> SCAN </button>
                    </div>
                </div>
            </form>

            <div class="row">
            </div>
            <div class="table-responsive">
                <div class="table-wrapper mt-0">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-8">
                                <h5 class="ibox-title font-weight-bold text-primary">Danh sách xe bán</h5>
                            </div>
                            <div class="col-sm-4 text-right">
                                <button type="button" wire:click="add()" class="btn btn-primary mr-3"
                                    @if (!$addBtn) disabled @endif {{ $status ? 'hidden' : '' }}><i
                                        class="fa fa-plus"></i>
                                    Thêm mới</button>
                                <button type="button" {{ $order_id ? 'hidden' : '' }}
                                    class="btn btn-info" data-toggle="modal"
                                    data-target="#modal-form-import"><i class="fa fa-upload" aria-hidden="true"></i>
                                    IMPORT FILE
                                </button>

                            </div>
                        </div>
                    </div>
                    @livewire('component.list-input-motorbike', ['type'=>1])
                </div>
            </div>
            <div class="form-group row">
                <div class="col-12 text-center">
                    <button {{ $status ? 'hidden' : '' }} name="submit" type="submit" wire:click='store'
                        class="btn btn-primary">
                        @if ($order_id)
                            Cập nhật hóa đơn
                        @else
                            Tạo hóa đơn bán buôn
                        @endif
                    </button>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.partials.button._importForm')
</div>
<script>
    window.livewire.on('close-modal-import', () => {
        document.getElementById('modal-form-import').click();
    })
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
            $('#sellerId').on('change', function(e) {
                var data = $('#sellerId').select2("val");
                @this.set('sellerId', data);
            });
            $('#technicalId').on('change', function(e) {
                var data = $('#technicalId').select2("val");
                @this.set('technicalId', data);
            });
        })
</script>
