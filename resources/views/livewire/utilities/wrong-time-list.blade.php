<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Danh sách xe nhập hàng muộn</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="SerialNumber" class="col-1 col-form-label">Số khung</label>
                        <div class="col-3">
                            <input id="SerialNumber" wire:model.debounce.1000ms="searchChassicNo" name="searchChassicNo"
                                type="text" class="form-control">
                        </div>
                        <label for="EngineNumber" class="col-1 col-form-label ">Số máy</label>
                        <div class="col-3">
                            <input id="SerialNumber" wire:model.debounce.1000ms="searchEngineNo" name="searchEngineNo"
                                type="text" class="form-control">
                        </div>
                        <label for="Model" class="col-1 col-form-label ">Model</label>
                        <div class="col-3">
                            <select name="Model" id="Model" class="custom-select"
                                wire:model.debounce.1000ms="searchModel">
                                <option value="">--Chọn--</option>
                                @foreach ($models as $model)
                                    <option value="{{ $model }}">{{ $model }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="Color" class="col-1 col-form-label ">Màu xe</label>
                        <div class="col-3">
                            <select name="Color" id="Color" class="custom-select"
                                wire:model.debounce.1000ms="searchColor">
                                <option value="">--Chọn--</option>
                                @foreach ($colors as $color)
                                    <option value="{{ $color }}">{{ $color }}</option>
                                @endforeach
                            </select>
                        </div>

                        <label for="Time" class="col-1 col-form-label ">Thời gian</label>
                        @include('layouts.partials.input._inputDateRanger')
                    </div>

                    <div class="form-group row justify-content-center">
                        @include('layouts.partials.button._reset')
                    </div>

                </form>
                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="category-table_filter" class="dataTables_filter">
                                <button @if (count($data) == 0) disabled @endif name="submit" data-target="#exportModal"
                                    data-toggle="modal" type="button" class="btn btn-warning add-new"><i
                                        class="fa fa-file-excel-o"></i> Export file</button>
                            </div>
                        </div>
                    </div>
                    @if (count($data) > 0)
                        {{ $data->links() }}
                    @endif
                    <div class="row">
                        <div class="col-sm-12 table-responsive">
                            <table class="table table-striped table-bordered dataTable no-footer"
                                id="category-table" cellspacing="0" width="100%" role="grid"
                                aria-describedby="category-table_info" style="width: 100%;">
                                <thead>
                                    <tr role="row">
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            aria-sort="ascending" aria-label="ID: activate to sort column descending"
                                            style="width: 164.5px;">ID</th>
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
                                            style="width: 60px;" wire:click="sorting('color')">Màu xe</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 100px;">Số lượng</th>
                                        <th class="{{ $key_name == 'actual_arrival_date_time' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 130px;" wire:click="sorting('actual_arrival_date_time')">Số
                                            ngày chậm</th>
                                        <th class="{{ $key_name == 'physical_status' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click="sorting('physical_status')">Trạng thái
                                        </th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164,5px;">Nhà cung cấp</th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    @forelse ($data as $value)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td class="sorting_1">IM_00{{ $value->id }}</td>
                                            <td>
                                                {{ $value->chassic_no }}
                                            </td>
                                            <td>{{ $value->engine_no }}</td>
                                            <td>{{ $value->model_type }}</td>
                                            <td>
                                                {{ $value->color }}
                                            </td>
                                            <td>1</td>
                                            <td>
                                                {{ Carbon\Carbon::parse($value->eta)->diffInDays(Carbon\Carbon::parse($value->actual_arrival_date_time)) }}
                                                ngày</span>
                                            </td>
                                            <td>
                                                @if ($value->physical_status == App\Enum\EHmsReceivePlan::STATUS_BLANK)
                                                    <span class="badge badge-danger"> Blank </span>
                                                @else
                                                    <span class="badge badge-success"> Receive Ok </span>
                                                @endif
                                            </td>
                                            <td>
                                                HVN
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center text-danger">Không có bản ghi nào.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if (count($data) > 0)
                            {{ $data->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="exampleModalLabel">Tải file excel xuống</h2>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xuất file không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-basic" data-dismiss="modal">Quay lại</button>
                    <button type="button" wire:click="export" class="btn btn-primary" data-dismiss="modal"
                        id='btn-upload-film'>Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
</div>
