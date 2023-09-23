<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">CHUYỂN KHO XE MÁY</div>
        </div>

        <div class="ibox-body">
            <form>
                <div class="form-group row">
                    <label for="tranferDate" class="col-1 col-form-label">Ngày chuyển</label>
                    <div class="col-3">
                        <input type="date" id="tranferDate" name="tranferDate" class="form-control"
                        max='{{ date('Y-m-d') }}' wire:model.lazy="tranferDate">
                        @error('tranferDate')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                    <label for="StorageDestination" class="col-1 col-form-label">Kho đích <span class="text-danger">*</span></label>
                    <div class="col-3">
                        <select wire:model="destinationWarehouseId" id="destinationWarehouse"
                            class="custom-select select2-box">
                            <option value="">Chọn kho đích</option>
                            @foreach ($warehouseDetinationList as $item)
                                <option value="{!! $item['id'] !!}">
                                    {!! $item['name'] !!}</option>
                            @endforeach
                        </select>
                        @error('destinationWarehouseId')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                    <label for="StorageSource" class="col-1 col-form-label">Kho nguồn <span class="text-danger">*</span></label>
                    <div class="col-3">
                        <select wire:model="sourceWarehouseId" id="sourceWarehouse" class="custom-select select2-box">
                            <option value="">Chọn kho nguồn</option>
                            @foreach ($warehouseSourceList as $item)
                                <option value="{!! $item['id'] !!}">
                                    {!! $item['name'] !!}</option>
                            @endforeach
                        </select>
                        @error('sourceWarehouseId')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="engineNoId" class="col-1 col-form-label ">Số máy</label>
                    <div class="col-3">
                        <select wire:model="engineNoId" id="engineNoId" name="engineNoId"
                            class="custom-select select2-box">
                            <option value="">Chọn số máy</option>
                            @foreach ($engineNoList as $item)
                                <option value="{{ $item }}">
                                    {{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="chassicNoId" class="col-1 col-form-label">Số khung</label>
                    <div class="col-3">
                        <select wire:model="chassicNoId" id="chassicNoId" name="chassicNoId"
                            class="custom-select select2-box">
                            <option value="">Chọn số khung</option>
                            @foreach ($chassicNoList as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 text-center mt-3">
                        <div wire:click="$emit('addMotobike')" class="btn btn-primary"><i class="fa fa-plus"></i>
                            Thêm</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="BarCode" class="col-md-1 col-form-label">Bar code xe</label>
                    <div class="col-md-3">
                        <input wire:model.lazy="barCode" id="BarCode" name="BarCode" placeholder="Bar code xe"
                            type="text" class="form-control">
                        @error('barCode')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                    <div class="col-md-2"> <button type="button" wire:click="addBarCode('{{ $barCode }}')"
                            class="btn btn-info add-new"><i class="fa fa-search"></i> SCAN </button></div>
                </div>
            </form>


            <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                <div class="row mt-5">
                    <div class="col-sm-12 col-md-6">
                        <h5 class="ibox-title font-weight-bold text-primary">Danh sách xe xuất kho</h5>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div id="category-table_filter" class="dataTables_filter">
                            <button name="submit" type="submit" class="btn btn-info add-new" data-toggle="modal"
                                data-target="#modal-form-import"><i class="fa fa-file-excel-o"></i> Import file</button>
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
                                    <th class="{{ $key_name == 'chassic_no' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 164.5px;" wire:click="sorting('chassic_no')">Số khung</th>
                                    <th class="{{ $key_name == 'engine_no' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 164.5px;" wire:click="sorting('engine_no')">Số máy</th>
                                    <th class="{{ $key_name == 'model_type' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 164.5px;" wire:click="sorting('model_type')">Model</th>
                                    <th class="{{ $key_name == 'color' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 164.5px;" wire:click="sorting('color')">Màu xe</th>
                                    <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 164.5px;">Số lượng</th>
                                    <th aria-controls="category-table" rowspan="1" colspan="1" style="width: 100.5px;">
                                        Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tranferMotobikeList as $key => $motobike)
                                    <tr data-parent="" data-index="1" role="row" class="odd">
                                        <td>{{ $key }}</td>
                                        <td>
                                            {{ $motobike['chassic_no'] }}
                                        </td>
                                        <td>{{ $motobike['engine_no'] }}</td>
                                        <td>{{ $motobike['model_type'] }}</td>
                                        <td>
                                            {{ $motobike['color'] }}
                                        </td>
                                        <td>
                                            {{ $motobike['quantity'] }}
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-danger delete-category btn-xs m-r-5"
                                                wire:click="remove({{ $key }})" data-toggle="tooltip"
                                                data-original-title="Xóa"><i class="fa fa-trash font-14"></i></a>
                                        </td>
                                    </tr>
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
            <div class="form-group row justify-content-end mt-5">
                <div wire:click="tranferMotobike" class="btn btn-info"><i class="fa fa-exchange"></i> Chuyển
                    kho</div>
            </div>
        </div>
    </div>
    @include('layouts.partials.button._importForm')
</div>
@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#sourceWarehouse').on('change', function(e) {
                let data = e.target.value;
                @this.set('sourceWarehouseId', data);
                window.livewire.emit('changeSource', data)
            });
            $('#destinationWarehouse').on('change', function(e) {
                let data = e.target.value;
                @this.set('destinationWarehouseId', data);
                window.livewire.emit('changeDestination', e.target.value)
            });

            $('#chassicNoId').on('change', function(e) {
                let data = e.target.value;
                @this.set('chassicNoId', data);
                window.livewire.emit('changeChassicNo', data);
            });
            $('#engineNoId').on('change', function(e) {
                let data = e.target.value;
                @this.set('engineNoId', data);
                window.livewire.emit('changeEngineNo', data);
            });
            setDatePickerUI();
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
