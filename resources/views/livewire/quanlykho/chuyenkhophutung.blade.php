<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">Quản lý chuyển kho</div>
        </div>

        <div class="ibox-body">
            <form>
                <div class="form-group row">
                    <label for="tranferDate" class="col-1 col-form-label">Ngày chuyển</label>
                    <div class="col-3">
                        <input type="date" id="tranferDate" name="tranferDate" class="form-control"
                            max='{{ now()->format('Y-m-d') }}' wire:model="tranferDate">
                        @error('tranferDate')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                    <label for="positionSourceWarehouse" class="col-1 col-form-label" style="padding-right: 0px">Vị
                        trí kho nguồn <span class="text-danger" {{ checkRoute('show')?'hidden':'' }}>*</span></label>
                    <div class="col-3">
                        <select wire:model="positionSourceWarehouseId" id="positionSourceWarehouseId"
                            class="custom-select select2-box">
                            <option value="">Chọn vị trí nguồn</option>
                            @foreach ($positionSourceWarehouseList as $item)
                                <option value="{!! $item['id'] !!}">
                                    {!! $item['name'] !!}</option>
                            @endforeach
                        </select>
                        @error('positionSourceWarehouseId')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                    <label for="positionDestinationWarehouse" class="col-1 col-form-label">Vị trí kho đích <span class="text-danger" {{ checkRoute('show')?'hidden':'' }}>*</span></label>
                    <div class="col-3">
                        <select wire:model="positionDestinationWarehouseId" id="positionDestinationWarehouseId"
                            class="custom-select select2-box">
                            <option value="">Chọn vị trí kho đích</option>
                            @foreach ($positionDetinationWarehouseList as $item)
                                <option value="{!! $item['id'] !!}">
                                    {!! $item['name'] !!}</option>
                            @endforeach
                        </select>
                        @error('positionDestinationWarehouseId')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="accessoryCode" class="col-1 col-form-label">Mã phụ tùng</label>
                    <div class="col-3">
                        <select wire:model="accessoryCode" id="accessoryCode" name="accessoryCode"
                            class="custom-select select2-box">
                            <option value="">Chọn mã phụ tùng</option>
                            @foreach ($accessoryCodeList as $item)
                                <option value="{!! $item !!}">
                                    {!! $item !!}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="quatity" class="col-1 col-form-label ">Số lượng</label>
                    <div class="col-3">
                        <input wire:model="quatity" type="number" class="form-control" />
                        @error('quatity')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                </div>

                <div class="form-group row justify-content-center btn-group-mt">
                    <div>
                        <div wire:click="$emit('addAccessory')" class="btn btn-primary"><i class="fa fa-plus"></i>
                            Thêm</div>
                    </div>
                </div>
            </form>


            <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                <div class="row mt-5">
                    <div class="col-sm-12 col-md-6">
                        <h5 class="ibox-title font-weight-bold text-primary">Danh sách phụ tùng xuất kho</h5>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div id="category-table_filter" class="dataTables_filter">
                            <button name="submit" type="submit" class="btn btn-info add-new" data-toggle="modal"
                                data-target="#modal-form-import"><i class="fa fa-upload"></i> Import file</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 table-responsive">
                        <table class="table table-striped table-bordered dataTable no-footer"
                            id="category-table" cellspacing="0" width="100%" role="grid"
                            aria-describedby="category-table_info" style="width: 100%;">
                            <thead>
                                <tr role="row">
                                    <th aria-controls="category-table" style="width: 20px;">
                                        STT
                                    </th>
                                    <th class="{{ $key_name == 'accessory_code' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 164.5px;" wire:click="sorting('accessory_code')">Mã phụ tùng
                                    </th>
                                    <th class="{{ $key_name == 'name' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 164.5px;" wire:click="sorting('name')">Tên phụ tùng</th>
                                    <th class="{{ $key_name == 'position' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 164.5px;" wire:click="sorting('position')">Vị trí lưu kho</th>
                                    <th class="{{ $key_name == 'amount_in_warehouse' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 164.5px;" wire:click="sorting('amount_in_warehouse')">Tồn kho
                                    </th>
                                    <th tabindex="0"
                                        class="{{ $key_name == 'quatity_tranfer' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        aria-controls="category-table" rowspan="1" colspan="1" style="width: 170px;"
                                        wire:click="sorting('quatity_tranfer')">Số lượng chuyển</th>
                                    <th tabindex="0"
                                        class="{{ $key_name == 'remain' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        aria-controls="category-table" rowspan="1" colspan="1" style="width: 150px;"
                                        wire:click="sorting('remain')">Còn lại</th>
                                    <th aria-controls="category-table" rowspan="1" colspan="1" style="width: 100.5px;">
                                        Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <div wire:loading class="loader"></div>
                                @forelse ($tranferAccessoryList as $key => $accessory)
                                    @livewire('component.tranfer-accessory-item',['accessoryItem' =>
                                    $accessory,'index'=>$key],
                                    key($accessory['accessory_code']))
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center">Không có bản ghi nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="form-group row justify-content-end mt-5 mr-1">
                <div wire:click="tranferAccessory" class="btn btn-info"><i class="fa fa-exchange"></i> Chuyển
                    kho</div>
            </div>
        </div>
    </div>

    @include('layouts.partials.button._importForm')
</div>
@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#positionSourceWarehouseId').on('change', function(e) {
                let data = e.target.value;
                @this.set('positionSourceWarehouseId', data);
                window.livewire.emit('changeSource', data)
            });
            $('#positionDestinationWarehouseId').on('change', function(e) {
                let data = e.target.value;
                @this.set('positionDestinationWarehouseId', data);
                window.livewire.emit('changeDestination', e.target.value)
            });

            $('#accessoryCode').on('change', function(e) {
                let data = e.target.value;
                @this.set('accessoryCode', data);
                window.livewire.emit('changeAccessoryCode', data);
            });

            setDatePickerUI();
        });

        document.addEventListener('setTranferDatePicker', function() {
            setDatePickerUI();
        });
        document.addEventListener('closeModalImport', function() {
            $('#modal-form-import').modal('toggle');
        });

        function setDatePickerUI() {
            $("#tranferDate").kendoDatePicker({
                format: "dd/MM/yyyy"
            });
            var tranferDate = $("#tranferDate").data("kendoDatePicker");
            tranferDate.bind("change", function() {
                var value = this.value();
                if (value != null) {
                    window.livewire.emit('setTranferDate', {
                        ['tranferDate']: this.value() ? this.value()
                            .toLocaleDateString('en-US') : null
                    });
                }
            });
        };
    </script>
@endsection
