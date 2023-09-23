<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Danh sách đơn hàng bảo hành khẩn</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="OrderCode" class="col-1 col-form-label">Mã đơn hàng</label>
                        <div class="col-3">
                            <input id="OrderCode" name="OrderCode" type="text" class="form-control"
                                wire:model.debounce.1000ms="searchCode">
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
                                            style="width: 164.5px;">ID
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" style="width: 164.5px;">Po_number</th>
                                        <th class="sorting" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" style="width: 164.5px;">Po_date</th>
                                        <th class="sorting" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" style="width: 164.5px;">Po_type</th>
                                        <th class="sorting" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" style="width: 164.5px;">Part_no</th>
                                        <th class="sorting" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" style="width: 164.5px;">Qty</th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    @forelse ($data as $value)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td>IM_00{{ $value->id ?? '' }}</td>
                                            <td>{{ $value->orderPlanDetails->order_number }}</td>
                                            <td>{{ $value->orderPlanDetails->orderPlan->po_date }}</td>
                                            <td>{{ $value->orderPlanDetails->orderPlan->part_order_type }}</td>
                                            <td>{{ $value->part_no ?? '' }}</td>
                                            <td>{{ $value->orderPlanDetails->quantity_requested }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center text-danger">Không có bản ghi nào.</td>
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
