
<div>
    <div wire:loading class="div-loading">
        <div class="loader"></div>
    </div>

    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thêm phụ tùng</div>
            </div>
            <div class="ibox-body">
                <div class="form-group row mt-1">
                    <label for="SupplierCode" class="col-1 col-form-label">Mã nhà CC <span
                            class="text-danger">*</span></label>
                    <div class="col-3">
                        <input {{ $isViewMode || $isEditMode ? 'disabled' : '' }} name="supplierCode"
                            wire:model.lazy="supplyCode" wire:change="changeStatusCode()"
                            placeholder="Mã nhà cung cấp" type="text" class="form-control form-red" required="required">
                        @error('supplyCode')<span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <label for="SupplierName" class="col-1 col-form-label">Nhà cung cấp <span
                            class="text-danger">*</span></label>
                    <div class="col-3">
                        <input {{ $isViewMode ? 'disabled' : '' }} id="SupplierName" wire:model.defer="name"
                            placeholder="Tên nhà cung cấp" type="text" @if ($supplyCode =='HVN' ) disabled @endif class="form-control"
                            required="required">
                        @error('name')<span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <label for="selectwarehouse" class="col-1 col-form-label">Kho nhập <span
                                class="text-danger"> *</span></label>
                    <div class="col-3">
                        <select {{$isViewMode?'disabled':''}}  class="custom-select select2-box"
                            id='selectwarehouse' wire:model='selectwarehouse'>
                        @if (!empty($warehouses))
                            @foreach($warehouses as $warehouse)
                                {{$itemwarehouseid =$warehouse->id ; }}
                                <option value='{{$warehouse->id}}'
                                    {{ $itemwarehouseid == $selectwarehouse ? 'selected' : '' }}
                                >{{$warehouse->name}} </option>
                            @endforeach
                        @endif
                        </select>
                    </div>
                </div>

                <!--TUDN ADD THIS ROW-->
                <div class="form-group row mt-1">
                    <label for="positionWarehouseId" class="col-1 col-form-label" style="padding-right: 0px">Vị
                        trí kho<span class="text-danger"> *</span></label>
                    <div class="col-3">
                        <select {{$isViewMode?'disabled':''}} class="custom-select select2-box"
                        id='positionWarehouseId'
                        wire:model="positionWarehouseId" >
                            @if(!empty($positionWarehouseList))
                                @foreach ($positionWarehouseList as $itemposition)
                                    {{$itempositionid=$itemposition->id;}}
                                    <option value="{{$itemposition->id}}"
                                        {{
                                            ($itempositionid==$positionWarehouseId) ? 'selected' : '' }}>
                                        {{$itemposition->name}}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('positionWarehouseId')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                    <label for="receivedate" class="col-1 col-form-label">Ngày nhập <span
                            class="text-danger">*</span></label>
                    <div class="col-3">
                        <input type="date" class="form-control input-date-kendo-edit" id="receivedate"
                        max='{{ date('Y-m-d') }}' wire:model.lazy="receivedate">
                    </div>

                    @if ($receivebyBillAndShip_number)
                        <label for="billandship_number" class="col-1 col-form-label">Nhận hàng Barcode </label>
                        <div class="col-3">
                            <input {{ $isViewMode ? 'disabled' : '' }} wire:model="billandship_number"
                            placeholder="Scan barcode phiếu nhận PT"
                            id='billandship_number'
                            type="text" class="form-control">
                        </div>
                    @endif

                </div>

                <!--END TUDN-->

                {{-- <div @if ($hvnHiddenDiv) style="display: none" @endif> --}}
                {{-- <div class="form-group row"> --}}
                {{-- <label for="PhoneNumber" class="col-1 col-form-label">Số điện thoại {{$hvnHiddenDiv}}</label> --}}
                {{-- <div class="col-3"> --}}
                {{-- <input {{$isViewMode?'disabled':''}} id="PhoneNumber" wire:model.defer="phone" --}}
                {{-- placeholder="Số điện thoại" type="text" --}}
                {{-- class="form-control" required="required"> --}}
                {{-- @error('phone')<span class="text-danger">{{$message}}</span> --}}
                {{-- @enderror --}}
                {{-- </div> --}}

                {{-- <label for="Email" class="col-1 col-form-label">Email</label> --}}
                {{-- <div class="col-3"> --}}
                {{-- <input {{$isViewMode?'disabled':''}} id="Email" wire:model.defer="email" placeholder="Email" --}}
                {{-- type="text" class="form-control"> --}}
                {{-- @error('email')<span class="text-danger">{{$message}}</span> --}}
                {{-- @enderror --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- @livewire('component.address') --}}
                {{-- </div> --}}

            @if (!$receivebyBillAndShip_number) <!-- start ko phai receive by bill barcode -->
                 @if ($receivebyPO)     <!--if receive by PO -->
                <div class="form-group row">
                    <label for="SupplierPO" class="col-1 col-form-label">Số PO </label>
                    <div @if (!$hvnHiddenDiv) style="display: none" @endif class="col-3">
                        <select wire:model.lazy="o_number" id="po_number" class="custom-select select2-box"
                            {{ $isViewMode ? 'disabled' : '' }}>
                            <option hidden>Chọn PO number</option>
                            @foreach ($po_number as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                        @error('o_number')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <script type="text/javascript">
                            document.addEventListener('DOMContentLoaded', function() {
                                $('#po_number').on('change', function(e) {
                                    var data = $('#po_number').select2("val");
                                    @this.set('o_number', data);
                                    window.livewire.emit('changeOrderNumber', data);
                                });
                            })
                        </script>
                    </div>

                </div>
                @endif <!--end if receive by PO -->
            @endif <!-- end ko phai receive by bill barcode -->

                <div  class="form-group row m-0 p-0 justify-content-end">

                    <label for="receivebyBillAndShip_number" class="col-4 col-form-label text-right"><input  type='checkbox' class="mr-3" wire:model='receivebyBillAndShip_number' id='receivebyBillAndShip_number'  selected >Scan phiếu nhận PT</label>
                    <label for="autoconvert" class="col-4 col-form-label text-right"><input  type='checkbox' class="mr-3" wire:model='autoconvert' id='autoconvert'  selected > Tự động chuyển mã PT</label>
                    <label for="receivebyPO" class="col-4 col-form-label text-right"><input  type='checkbox' class="mr-3" wire:model='receivebyPO' id='receivebyPO'  selected > Nhận theo PO </label>
                </div>


                @if (!$receivebyPO && !$receivebyBillAndShip_number)
                <div  class="form-group row">
                    <label for="BarCode" class="col-1 col-form-label" style="padding-right: 0px">Mã PT
                    <span class="text-danger"> *</span></label>
                    <div class="col-3">
                        <input id="barCode" wire:model.lazy="barCode"
                        placeholder="Mã phụ tùng" type="text"
                        wire:keydown.enter='getBarCodeInfo'
                            class="form-control" {{ $isViewMode ? 'disabled' : '' }}>
                        @error('barCode')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <label  for="barCodeName" class="col-1 col-form-label">Tên PT
                    <span class="text-danger"> *</span></label>
                    <div class="col-3">
                        <input id="barCodeName" wire:model.lazy="barCodeName" placeholder="tên phụ tùng" type="text"
                            class="form-control" {{ $isViewMode ? 'disabled' : '' }}>
                        @error('barCodeName')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div  class="form-group row">
                    <label  for="inputQty" class="col-1 col-form-label">Số lượng nhập
                    <span class="text-danger"> *</span></label>
                    <div class="col-3">
                        <input id="inputQty" wire:model.lazy="inputQty" placeholder="Số lượng" type="text"
                            class="form-control" {{ $isViewMode ? 'disabled' : '' }}>
                        @error('inputQty')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <label  for="inputPrice" class="col-1 col-form-label">Đơn giá
                    <span class="text-danger"> *</span></label>
                    <div class="col-3">
                        <input id="inputPrice" wire:model.lazy="inputPrice" placeholder="Đơn giá" type="text"
                            class="form-control" {{ $isViewMode ? 'disabled' : '' }}>
                        @error('inputPrice')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
                @endif



            @if (!$receivebyBillAndShip_number && !$receivebyPO)
                <div class="form-group row">
                     <div class="col-12" style='text-align:right'>
                            <button {{ ($isViewMode || $isDisable )? 'disabled' : '' }} type="button"
                                wire:click="addBarCode('{{ $barCode }}')" class="btn btn-info add-new"><i
                                    class="fa fa-plus"></i> THÊM
                            </button>
                    </div>
                </div>
            @endif
            <div class="col-md-12 text-right pr-0 mt-2">
                <button type="button" class="btn btn-info add-new" data-toggle="modal"
                    data-target="#modal-form-import"><i class="fa fa-upload"></i> IMPORT
                </button>
            </div>
                <div class="table-responsive">
                    <div class="table-wrapper" style="margin-top:0">
                        @livewire('component.list-input-receive-part',['type'=>3])
                    </div>
                </div>

            </div>
        </div>
    </div>
    @include('layouts.partials.button._importValidateForm')
    <script>

        document.addEventListener('livewire:load', function() {
            // Your JS here.
            var fromDate = new Date(); //new Date(date.getFullYear(), date.getMonth(), 1);
            $('#receivedate').kendoDatePicker({
                format: "dd/MM/yyyy"
            });

            //handle when user scan barcode
            //must set barcode function = enter
            $('#billandship_number').on('keyup', function(e) {
                if (e.key === 'Enter' || e.keyCode === 13) {
                    window.livewire.emit('receiveBillAndShipNumber', document.getElementById('billandship_number')
                            .value);
                        $("#billandship_number").focus();
                }
            });
                //load thong tin part trong PO
                /*
                $("#BarCode").on('keyup', function(e) {
                    if (e.key === 'Enter' || e.keyCode === 13) {
                        window.livewire.emit('getBarCodeInfo', document.getElementById('BarCode')
                            .value);
                    }

                }); */

                //add barcode and focus barcode
                $("#inputQty").on('keyup', function(e) {
                    if (e.key === 'Enter' || e.keyCode === 13) {
                        window.livewire.emit('addBarCode', document.getElementById('BarCode')
                            .value);
                        $("#BarCode").focus();
                    }
                });


        });
        window.livewire.on('resetInputBarCode', () => {
            document.getElementById('BarCode').value = '';
        });
        window.livewire.on('close-modal-import', () => {
            document.getElementById('modal-form-import').click();
        });
        window.livewire.on('close-modal-delete', () => {
            document.getElementById('close-modal-delete').click();
        })


        document.addEventListener('DOMContentLoaded', function() {
            $('#selectwarehouse').on('change', function(e) {
                var data = $('#selectwarehouse').val();
                @this.set('selectwarehouse', data);
            });

            $('#positionWarehouseId').on('change', function(e) {
                var data = $('#positionWarehouseId').val();
                @this.set('positionWarehouseId', data);
            });
        });



        window.addEventListener('setFocusItem', event => {
            var idx = event.detail.name;
           // document.getElementById(idx).focus();
        });
    </script>

</div>
