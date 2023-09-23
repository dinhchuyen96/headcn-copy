<div class="page-content fade-in-up" style='padding-top:0px !important' >
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">Bán lẻ phụ tùng</div>
        </div>
        <div class="ibox-body">
            <div class="form-group row mt-1">

                <label for="phone" class="col-1 col-form-label">Số điện thoại<span class="text-danger"> *</span></label>
                <div class="col-3">
                    <input  wire:model="phone"
                            placeholder="Số điện thoại" type="number" {{$status?'disabled':''}}
                           class="form-control form-red" required="required" >
                    @error('phone')<span class="text-danger">{{$message}}</span>@enderror
                </div>

                <label for="name" class="col-1 col-form-label">CMT/HC</label>
                <div class="col-3">
                    <input  id="name" wire:model.defer="identity_code" placeholder="CMT/HC" type="number"
                           class="form-control form-red" required="required" {{$status?'disabled':''}}>
                </div>

                <label for="name" class="col-1 col-form-label pr-0">Tên khách hàng<span class="text-danger"> *</span></label>
                <div class="col-3">
                    <input id="name" wire:model.defer="name" placeholder="Tên khách hàng" type="text" {{$status?'disabled':''}}
                           class="form-control" required="required">
                    @error('name')<span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">

                <label for="email" class="col-1 col-form-label">Email</label>
                <div class="col-3">
                    <input id="email" wire:model.defer="email" placeholder="Email" type="text" {{$status?'disabled':''}}
                           class="form-control" >

                </div>
            </div>

            @livewire('component.address')

            <div class="form-group row">
                    <label for="BarCode" class="col-md-1 col-form-label">Scan/Nhập mã PT</label>
                    <div class="col-md-3">

                        <select {{$status ?'disabled':''}}  class="custom-select select2-box"
                            id='BarCode' wire:model.lazy='barcode'>
                            <option value ="" hidden>Chọn phụ tùng</option>
                        @if (!empty($avaibleaccessories))
                            @foreach($avaibleaccessories as $part)
                            <option value='{{$part->code}}'
                                {{ $part->code==$barcode ? 'selected' : '' }} >
                                {{$part->code.'-'.$part->name}}
                            </option>
                            @endforeach
                        @endif
                        </select>

                    </div>
                    <label for="warehouse" class="col-md-1 col-form-label">Kho xuất<span class="text-danger"> *</span></label>
                    <div class="col-md-3">
                        <select {{$status || $order_id ?'disabled':''}}  class="form-control select2-box"
                            id='selectwarehouse' wire:model.lazy='selectwarehouse' >

                        @if (!empty($warehouses))
                            @foreach($warehouses as $warehouse)
                                <option value='{{$warehouse->id }}'
                                    {{ $selectwarehouse ==$warehouse->id ? 'selected' :'' }} >
                                    {{ $warehouse->name }} </option>
                            @endforeach
                        @endif
                        </select>
                    </div>

                    <label for="positionWarehouseId" class="col-1 col-form-label" style="padding-right: 0px">Vị
                        trí kho<span class="text-danger"> *</span>
                    </label>
                    <div class="col-3">
                        <select wire:model.lazy="positionWarehouseId" id="positionWarehouseId"
                            {{ $status ? 'disabled' : '' }} class="form-control select2-box ">
                            @if(!empty($positionWarehouseList))
                                @foreach ($positionWarehouseList as $itemposition)
                                    {{ $itempositionid=$itemposition->id; }}
                                    <option value="{{$itemposition->id}}"
                                        {{
                                            $positionWarehouseId==$itempositionid ? 'selected' : '' }}>
                                        {{$itemposition->name}}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('positionWarehouseId')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>

                    <label for="transactionDate" class="col-1 col-form-label">Ngày bán
                        {{-- <span class="text-danger">*</span> --}}
                    </label>
                    <div class="col-3">
                        <input type="date" id="transactionDate" class="form-control date-picker input-date-kendo"
                            max='{{ date('Y-m-d') }}' {{ $status ? 'disabled' : '' }} wire:model.lazy="transactionDate">
                        @error('transactionDate')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                </div>

            <div class="form-group row" style="display:none">
                <label for="name" class="col-1 col-form-label pr-0">Mã khách hàng<span class="text-danger"> *</span></label>
                    <div class="col-3">
                        <input id="name" wire:model.lazy="code"  placeholder="Mã khách hàng" type="text" {{$status?'disabled':''}}
                            class="form-control form-red" required="required">
                        @error('code')<span class="text-danger">{{$message}}</span>
                        @enderror
                </div>
            </div>

            <div class="form-group row justify-content-end" style="padding: 0 20px;">
                        <button  @if ($status) style='display:none' @endif type="button" wire:click="addBarCode('{{$barcode}}')" class="btn btn-primary add-new"><i
                                class="fa fa-plus"></i> THÊM </button>
                                <div class="virtual">
                    <input type='checkbox' id='chkIsVirtual' class="ml-3"
                    wire:model="chkIsVirtual" {{$status ?'disabled':''}}
                    > Đơn ảo
                </div>

            </div>
            @livewire('component.list-input-part-wholesale',['type'=>2])
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#BarCode').on('change', function (e) {
                var data = $('#BarCode').select2("val");
            @this.set('barcode', data);
            });
            $('#selectwarehouse').on('change', function (e) {
                var data = $('#selectwarehouse').select2("val");
            @this.set('selectwarehouse', data);
            });
            $('#positionWarehouseId').on('change', function (e) {
                var data = $('#positionWarehouseId').select2("val");
            @this.set('positionWarehouseId', data);
            });
        })

        document.addEventListener('livewire:load', function () {
            $(function () {
                $("#BarCode").on('keyup', function (e) {
                    if (e.key === 'Enter' || e.keyCode === 13) {
                        window.livewire.emit('addBarCode', document.getElementById('BarCode').value);
                    }
                });
            });
        });
        window.livewire.on('resetInputBarCode', () => {
            document.getElementById('BarCode').value='';
        });
        window.livewire.on('close-modal-import', ()=>{
            document.getElementById('modal-form-import').click();
        });
        window.livewire.on('close-modal-delete', ()=>{
            document.getElementById('close-modal-delete').click();
        })
        window.addEventListener('hello', event => {
            let titleMessage = 
            'Thêm mới thành công. Bạn có muốn in phiếu bán lẻ phụ tùng bằng pdf không?';
            Swal.fire({
                title: titleMessage,
                icon: 'success',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy bỏ',
                showCancelButton: true,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = event.detail.url;
                    window.open(
                        url,
                        '_blank'
                    );
                }
            })
        });
    </script>
</div>
