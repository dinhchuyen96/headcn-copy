<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Tìm kiếm thông tin</div>
            </div>
            <div class="ibox-body">
                <div class="row">
                <div class="col-4">
                        <div class="form-group row">
                            <label for="SupplyCode" class="col-2 col-form-label">Model</label>
                            <div class="col-10">
                                <input id="modelXe" wire:model.debounce.500ms="modelXe" type="text"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="SupplyCode" class="col-2 col-form-label">Thể loại</label>
                            <div class="col-10">
                                <input id="theLoai" wire:model.debounce.500ms="theLoai" type="text"
                                    class="form-control">
                            </div>
                        </div>

                    </div>
                    <div class="col-4">
                        <div class="form-group row">
                            <label for="SupplyCode" class="col-2 col-form-label">Số khung</label>
                            <div class="col-10">
                                <input id="soKhung" wire:model.debounce.500ms="soKhung" type="text"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="SupplyCode" class="col-2 col-form-label">Số máy</label>
                            <div class="col-10">
                                <input id="soMay" wire:model.debounce.500ms="soMay" type="text" class="form-control">
                            </div>
                        </div>
                        
                    </div>

                    <div class="col-4">
                        <div class="from-group row">
                            <label for="Time" class="col-2 col-form-label ">Thời gian</label>
                            <div class="col-10 row pr-0" wire:ignore>
                                <div class="col-5 pr-0">
                                    <input type="date" class="form-control " id="tranferDatefrom"
                                        name="tranferDatefrom" max="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-2 justify-content-center align-items-center">
                                    <p class="text-center pt-2">～</p>
                                </div>
                                <div class="col-5 pr-0 pl-0">
                                    <input type="date" class="form-control " id="tranferDateto" name="tranferDateto"
                                        max="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="form-group row justify-content-center">
                    @include('layouts.partials.button._reset')
                </div>

                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    @if (count($dataAC) > 0)
                        {{ $dataAC->links() }}
                    @endif
                    <div class="row">
                        <div class="col-sm-12 table-responsive">
                            <div wire:loading class="loader"></div>
                            <table class="table table-striped table-bordered dataTable no-footer"
                                id="category-table" cellspacing="0" width="100%" role="grid"
                                aria-describedby="category-table_info" style="width: 100%;">
                                <thead>
                                    <tr role="row">
                                        <th wire:click="sorting('chassic_no')"
                                            class="@if ($key_name == 'chassic_no')
                                    {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 174.5px;">Số khung</th>
                                        <th wire:click="sorting('engine_no')"
                                            class="@if ($key_name == 'engine_no')
                                            {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 174.5px;">Số máy</th>
                                        <th wire:click="sorting('model_code')"
                                            class="@if ($key_name == 'model_code')
                                        {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 174.5px;">Model</th>
                                        <th wire:click="sorting('model_code')"
                                            class="@if ($key_name == 'model_code')
                                        {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 194.5px;">Phân loại đời xe</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 174.5px;">Lần chuyển kho</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 174.5px;">Ngày chuyển</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 174.5px;">Chuyển Từ Kho</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 174.5px;">Tới Kho</th>
                                    </tr>
                                    <thead>
                                    <tbody>
                                        @forelse ($data as $key => $item)
                                            <tr data-parent="" data-index="1" role="row" class="data_table">
                                                {{-- <td class="sorting_1">{{($data->currentPage() - 1) * $data->perPage() + $loop->iteration}}</td> --}}
                                                @foreach (explode('@##@', $key) as $row)
                                                    <td>{{ $row }}</td>
                                                @endforeach
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            <tr>
                                            </tr>
                                            <p hidden>{{ $ship = 0 }}</p>
                                            @foreach ($item as $row)
                                                <tr>
                                                    <p hidden>{{ $ship += 1 }}</p>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{{ $ship }}</td>
                                                    <td>{{ reFormatDate($row->tranfer_date, 'd/m/Y') }}</td>
                                                    <td>{{ $row->WarehouseFromName }}</td>
                                                    <td>{{ $row->WarehouseToName }}</td>

                                                <tr>
                                            @endforeach
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center text-danger">Không có bản ghi </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                            </table>
                        </div>
                    </div>
                    @if (count($dataAC) > 0)
                        {{ $dataAC->links() }}
                    @endif
                    @include('layouts.partials.button._deleteForm')
                    @include('layouts.partials.button._exportForm')
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setDatePickerUI();
    });

    var getTimeTranferto;

    function setDatePickerUI() {
        $("#tranferDatefrom").kendoDatePicker({
            max: new Date(),
            value: new Date(),
            format: 'dd/MM/yyyy',
            change: function() {
                if (this.value() != null) {
                    window.livewire.emit('setTranferDatefrom', {
                        ['timefrom']: this.value() ? this.value().toLocaleDateString('en-US') : null
                    });
                }
            }
        });
        $("#tranferDateto").kendoDatePicker({
            max: new Date(),
            value: new Date(),
            format: 'dd/MM/yyyy',
            change: function() {
                if (this.value() != null) {
                    window.livewire.emit('setTranferDateto', {
                        ['timeto']: this.value() ? this.value().toLocaleDateString('en-US') : null
                    });
                }
            }
        });
        $("#tranferDateto").change(function() {
            getTimeTranferto = $("#tranferDateto").data("kendoDatePicker").value().toLocaleDateString('en-US');
            var time =  getTimeTranferto.split('/');
            $("#tranferDatefrom").data("kendoDatePicker").setOptions({
                max: new Date(time[2], time[0]-1, time[1])
            });
        });
        $("#tranferDatefrom").change(function() {
            getTimeTranferto = $("#tranferDatefrom").data("kendoDatePicker").value().toLocaleDateString('en-US');
            var time =  getTimeTranferto.split('/');
            $("#tranferDateto").data("kendoDatePicker").setOptions({
                min: new Date(time[2], time[0]-1, time[1])
            });
        });
    };
</script>
