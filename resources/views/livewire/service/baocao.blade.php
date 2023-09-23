<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Báo cáo sửa chữa thông thường</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <div class="col-4">
                            <input autocomplete="off" name="search" wire:model.debounce.1000ms="search" class="form-control size13" type="text" placeholder="Tìm kiếm..."/>
                        </div>
                        <label for="Time" class="col-1 col-form-label ">Thời gian</label>
                        @include('layouts.partials.input._inputDateRanger')
                    </div>
                </form>
                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer pt-4">
                    {{--                    <div class="row">--}}
                    {{--                        <div class="col-sm-12 col-md-12">--}}
                    {{--                            <div id="category-table_filter" class="dataTables_filter">--}}
                    {{--                                <button @if (count($data) == 0) disabled @endif name="submit"--}}
                    {{--                                        data-target="#exportModal" data-toggle="modal" type="button"--}}
                    {{--                                        class="btn btn-warning add-new"><i class="fa fa-file-excel-o"></i> Export file</button>--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    <div class="row">
                        <div wire:loading class="loader"></div>
                        <div class="col-sm-12 table-responsive">
                            <table class="table table-bordered dataTable no-footer"
                                   id="category-table" cellspacing="0" width="100%" role="grid"
                                   aria-describedby="category-table_info" style="width: 100%;">
                                <thead>
                                <tr role="row">
                                    <th rowspan="1" colspan="1"
                                        style="width: 200px;">Nhân viên</th>
                                    <th rowspan="1" colspan="1"
                                        style="">Ngày nhận xe</th>
                                    <th rowspan="1" colspan="1"
                                        style="">Nội dung sửa chữa</th>
                                    <th rowspan="1" colspan="1"
                                        style="">Biển số xe</th>
                                    <th rowspan="1" colspan="1"
                                        style="">Loại xe</th>
                                    <th rowspan="1" colspan="1"
                                        style="">Tổng tiền</th>

                                </tr>
                                </thead>

                                <tbody>
                                @forelse ($data as $key => $item)
                                    @foreach($item->repairBills as $i => $repairBill)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            @if (!$i)
                                                <td rowspan="{{ $item->repairBills->count() + 1 }}">{{ $item->name }}<br/> ID: {{ $item->username }}</td>
                                            @endif
                                            <td>{{ $repairBill->created_at->format('d/m/Y') }}</td>
                                            <td>{{ @$repairBill->content_request }}</td>
                                            <td>{{ @$repairBill->motorbike->motor_numbers }}</td>
                                            <td>{{ @$repairBill->motorbike->model_code }}</td>
                                            <td>{{ number_format(@$repairBill->order->total_money) }}</td>
                                        </tr>
                                        @if ($i == $item->repairBills->count() - 1)
                                            <tr data-parent="" data-index="1" role="row" class="odd">
                                                <td colspan="5" class="text-right font-weight-bold">{{ number_format(@$item->repairBills->sum('order.total_money')) }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-danger">Không có bản ghi nào.</td>
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
