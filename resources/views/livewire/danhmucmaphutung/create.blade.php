<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thông tin mã phụ tùng</div>
            </div>
            <div class="ibox-body">
                <div class="form-group row">
                    <label for="code" class="col-1 col-form-label ">Mã phụ tùng <span
                            class="text-danger"> *</span></label>
                    <div class="col-3">
                        <input id="code" wire:model.defer="code"
                            placeholder="Mã phụ tùng" type="text" class="form-control">
                        @error('code')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <label for="nameCategory" class="col-1 col-form-label ">Tên<span
                            class="text-danger"> *</span></label>
                    <div class="col-3">
                        <input id="nameCategory" wire:model.defer="nameCategory"
                            placeholder="Tên phụ tùng" type="text" class="form-control">
                        @error('nameCategory')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <label for="unit" class="col-1 col-form-label ">Đơn vị<span
                            class="text-danger"> *</span></label>
                    <div class="col-3">
                        <input id="unit" wire:model.defer="unit"
                            placeholder="Đơn vị tính" type="text" class="form-control">
                        @error('unit')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                </div>

                <div class="form-group row">
                    <label for="parentcode" class="col-1 col-form-label ">Mã PT cha </label>
                    <div class="col-3">
                        <select class="custom-select select2-box"
                            id='parentcode' wire:model='parentcode' >
                            <option value="" hidden> Chọn phụ tùng</option>
                            @if (!empty($parentcodes))
                                @foreach($parentcodes as $key => $value)
                                    <option value='{{$parentcodes[$key]}}'>
                                        {{$parentcodes[$key]}}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('parentcode')<span class="text-danger">{{ $message }}</span>@enderror

                        <script type="text/javascript">
                            document.addEventListener('DOMContentLoaded', function() {
                                $('#parentcode').on('change', function(e) {
                                    var data = $('#parentcode').select2("val");
                                    @this.set('parentcode', data);

                                });
                            })
                        </script>

                    </div>


                    <label for="parentname" class="col-1 col-form-label ">Tên PT</label>
                    <div class="col-3">
                        <input id="parentname" wire:model.defer="parentname"
                            placeholder="Tên phụ tùng cha" disabled type="text" class="form-control">
                    </div>

                    <label for="changerate" class="col-1 col-form-label ">Tỉ lệ</label>
                    <div class="col-3">
                        <input id="changerate" wire:model.defer="changerate"
                            placeholder="Tỉ lệ quy đổi" type="text" class="form-control">
                        @error('changereate')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                </div>

                <div class="form-group row mt-1">
                    <label for="selectwarehouse" class="col-1 col-form-label">Kho nhập mặc định<span
                                class="text-danger"> *</span></label>
                    <div class="col-3">
                        <select class="form-control"
                            id='selectwarehouse' wire:model='selectwarehouse'
                            wire:change="onChangeWarehouse"   >
                        @if (!empty($warehouses))
                            @foreach($warehouses as $warehouse)
                                $itemwarehouseid = $warehouse->id;
                                <option value='{{$warehouse->id}}'
                                    {{$itemwarehouseid==$selectwarehouse ? 'selected' : '' }}
                                >{{$warehouse->name}} </option>
                            @endforeach
                        @endif
                        </select>
                    </div>

                    <label for="positionWarehouseId" class="col-1 col-form-label" style="padding-right: 0px">Vị
                        trí kho<span class="text-danger"> *</span></label>
                    <div class="col-3">
                        <select wire:model="positionWarehouseId" id="positionWarehouseId"
                            {{ $isViewMode ? 'disabled' : '' }} class="form-control">
                            @if(!empty($positionWarehouseList))
                                @foreach ($positionWarehouseList as $itemposition)
                                    $itempositionid=$itemposition->id;
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

                    <label for="netprice" class="col-1 col-form-label" style="padding-right: 0px">Giá bán đề xuất<span class="text-danger"> *</span></label>
                    <div class="col-3">
                        <input id="netprice" wire:model.defer="netprice"
                            placeholder="Giá HVN đề xuất" type="text" class="form-control">
                            @error('netprice')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <!--END TUDN-->

                <div class="form-group row justify-content-center btn-group-mt">
                    <div>
                        <a href="{{ route('danhmucmaphutung.danhsach.index') }}" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i>
                            Trở lại
                        </a>
                        <button type="button" class="btn btn-primary" wire:click.prevent="store()"><i
                            class="fa fa-plus"></i> Tạo mới</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
         document.addEventListener('DOMContentLoaded', function() {
            $('#selectwarehouse option').eq(0).prop('selected', true);
            $('#selectwarehouse').on('change', function(e) {
                let data = e.target.value;
                @this.set('selectwarehouse', data);
            });

            $('#positionWarehouseId option').eq(0).prop('selected', true);
            $('#positionWarehouseId').on('change', function(e) {
                let data = e.target.value;
                @this.set('positionWarehouseId', data);
            });
        });
    </script>
</div>
