@php
use App\Enum\EMotorbike;
@endphp

<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-body">
            <form>
                <div class="form-group row">
                    <label for="SerialNumber" class="col-1 col-form-label">Số khung</label>
                    <div class="col-3">
                        <input id="SerialNumber" wire:model.debounce.500ms='searchChassic' name="SerialNumber"
                            type="text" class="form-control">
                    </div>
                    <label for="EngineNumber" class="col-1 col-form-label ">Số máy</label>
                    <div class="col-3">
                        <input id="EngineNumber" wire:model='searchEngine' name="EngineNumber" type="text"
                            class="form-control">
                    </div>
                    <label for="Model" class="col-1 col-form-label ">Tên đời xe</label>
                    <div class="col-3">
                        <input id="model" wire:model.debounce.1000ms='searchModel' type="text" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    
                    <label for="Color" class="col-1 col-form-label ">Màu xe</label>
                    <div class="col-3">
                        <input wire:model.debounce.1000ms='searchColor' type="text" class="form-control">
                    </div>
                    <label for="SupplyName" class="col-1 col-form-label">Nhà CC</label>
                    <div class="col-3">
                        <select name="SupplyName" wire:model='searchSupplier' id="SupplyName"
                            class="custom-select select2-box">
                            @foreach ($supplierList as $key => $val)
                                <option value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="Time" class="col-1 col-form-label ">Thời gian nhập</label>
                    @include('layouts.partials.input._inputDateRangerNow')
                </div>
                <div class="form-group row">
                    <label for="searchStatus" class="col-1 col-form-label">Trạng thái</label>
                    <div class="col-3">
                        <select wire:model='searchStatus' class="custom-select select2-box" id="searchStatus">
                            @foreach ($statusList as $key => $val)
                                <option value="{{ $key }}">{{ $val }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <label class="col-1 col-form-label">Kho</label>
                    <div class="col-3">
                        <select wire:model="seachWarehouse" id="seachWarehouse" class="custom-select select2-box">
                            <option value="">--Chọn Kho--</option>
                            @foreach ($warehouseList as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row justify-content-center">
                    <div class="col-1">
                        @include('layouts.partials.button._reset')
                    </div>
                </div>

            </form>
            <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="category-table_filter" class="dataTables_filter">
                            <button name="submit" type="submit" class="btn btn-warning add-new"
                                {{ count($data) ? '' : 'disabled' }} data-target="#exportModal" data-toggle="modal"><i
                                    class="fa fa-file-excel-o"></i> Export file</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 table-responsive">
                        <table class="table table-striped table-bordered dataTable no-footer"
                            id="category-table" cellspacing="0" width="100%" role="grid"
                            aria-describedby="category-table_info"
                            style="width: 100%;display:block;overflow-x: scroll;white-space: nowrap;">
                            <thead>
                                <tr role="row">
                                    <th>STT
                                    </th>
                                    <th class="{{ $key_name == 'chassic_no' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 150.5px;" wire:click="sorting('chassic_no')">Số khung</th>
                                    <th class="{{ $key_name == 'engine_no' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 100.5px;" wire:click="sorting('engine_no')">Số máy</th>
                                    <th class="{{ $key_name == 'model_code' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 120.5px;" wire:click="sorting('model_code')">Đời xe</th>
                                    <th class="{{ $key_name == 'model_type' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 164.5px;" wire:click="sorting('model_type')">Phân loại</th>
                                    <th class="{{ $key_name == 'model_list' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 164.5px;" wire:click="sorting('model_list')">Danh mục</th>
                                    <th class="{{ $key_name == 'color' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 100.5px;" wire:click="sorting('color')">Màu xe</th>
                                    <th class="{{ $key_name == 'customer_id' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 100.5px;" wire:click="sorting('customer_id')">Trạng thái</th>
                                    <th class="{{ $key_name == 'price' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 120.5px;" wire:click="sorting('price')">Đơn giá</th>
                                    <th class="{{ $key_name == 'supply_name' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 120.5px;" wire:click="sorting('supply_name')">NCC</th>
                                    <th class="{{ $key_name == 'warehouse_name' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 50.5px;" wire:click="sorting('warehouse_name')">Kho</th>
                                    <th class="{{ $key_name == 'created_at' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 150.5px;" wire:click="sorting('created_at')">Ngày nhập</th>
                                    <th class="{{ $key_name == 'sell_date' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 150.5px;" wire:click="sorting('sell_date')">Ngày bán</th>
                                    <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 190.5px;">Thao tác</th>
                                </tr>
                            </thead>
                            <div wire:loading class="loader"></div>
                            <tbody>
                                @forelse ($data as $val)
                                    <tr data-parent="" data-index="1" role="row" class="odd">
                                        <td style="text-align: center;">
                                            {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                        </td>
                                        <td>{{ $val->chassic_no }}</td>
                                        <td>{{ $val->engine_no }}</td>
                                        <td>{{ $val->model_code }}</td>
                                        <td>{{ $val->model_list }}</td>
                                        <td>{{ $val->model_type }}</td>
                                        <td>{{ $val->color }}</td>
                                        <td class="text-center">
                                            @if (!empty($val->customer_id) && $val->status == EMotorbike::SOLD)
                                                <span class="badge badge-success">Đã xuất</span>
                                            @endif
                                            @if (empty($val->customer_id) && $val->status == EMotorbike::NEW_INPUT)
                                                <span class="badge badge-default">Mới nhập</span>
                                            @endif
                                            @if (empty($val->customer_id) && $val->status == EMotorbike::PROCESS)
                                                <span class="badge badge-warning">Chờ xử lý</span>
                                            @endif
                                            @if (empty($val->customer_id) && $val->status == EMotorbike::VITUAL)
                                                <span class="badge badge-primary">Bán ảo</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($val->price) }}</td>
                                        <td>{{ $val->supply_name ?? '' }}</td>
                                        <td>{{ $val->warehouse_name ?? '' }}</td>
                                        <td>{{ $val->buy_date ?? '' }}</td>
                                        <td>
                                            @if (!@empty($val->customer_id) && $val->status == 1)
                                                {{ $val->sell_date ?? '' }}
                                            @endif
                                        </td>
                                        <td class="text-center" style="margin-right: 0 ">
                                            <a href="{{ route('motorbikes.buy.index', ['id' => $val->order_id, 'show' => 'true']) }}"
                                                class="btn btn-info btn-xs" data-toggle="tooltip" target="_blank"
                                                data-original-title="Xem"><i class="fa fa-eye font-10"
                                                    style="font-size: 10px"></i></a>
                                            @if ($val->customer_id == null && $val->status != EMotorbike::VITUAL)
                                                <a href="{{ route('motorbikes.buy.index', ['id' => $val->order_id]) }}"
                                                    target="_blank" class="btn btn-primary btn-xs "
                                                    data-toggle="tooltip" data-original-title="Sửa"><i
                                                        class="fa fa-pencil font-10" style="font-size: 10px"></i></a>
                                                <a href="#" data-toggle="modal" data-target="#deleteModal"
                                                    class="btn btn-danger delete-category btn-xs" data-toggle="tooltip"
                                                    data-original-title="Xóa"
                                                    wire:click='deleteId({{ $val->id }})'><i
                                                        class="fa fa-trash font-10" style="font-size: 10px"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="14" class="text-center text-danger">Không có bản ghi nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if (count($data) > 0)
                    {{ $data->links() }}
                @endif
            </div>
        </div>
    </div>
    @include('livewire.common.modal._modalDelete')
    @include('livewire.common.modal._modalExport')
</div>
@section('js')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            $('#SupplyName').on('change', function(e) {
                var data = $('#SupplyName').select2("val");
                @this.set('searchSupplier', data);
            });
            $('#searchStatus').on('change', function(e) {
                var data = $('#searchStatus').select2("val");
                @this.set('searchStatus', data);
            });
            $('#seachWarehouse').on('change', function(e) {
                var data = $('#seachWarehouse').select2("val");
                @this.set('seachWarehouse', data);
            });
        })
    </script>
@endsection
