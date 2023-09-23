<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title col-4">Báo cáo phụ tùng theo rank</div>
                <label class="col-1 col-form-label">Tháng</label>
                <div class="col-2">
                    <input type="date" class="form-control input-date-kendo-edit" id="reportdate"
                        max='{{ date('Y-m-d') }}' wire:model.lazy="reportdate">
                </div>
                <label class="col-1 col-form-label ">Phụ tùng</label>
                <div class="col-2">
                    <input id="searchPartNo" name="searchPartNo" type="text" class="form-control"
                    wire:model.debounce.1000ms="searchPartNo" autocomplete="off">
                </div>
            </div>
            <div class="ibox-body">
                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row mt-5">
                        <div class="col-sm-12 text-right">
                            <button data-target="#ModalExport" data-toggle="modal" type="button" class="btn btn-warning add-new"
                                {{ count($accessories) ? '' : 'disabled' }}><i class="fa fa-file-excel-o"></i> Export
                                file</button>
                        </div>
                    </div>
                    @if (count($accessories) > 0)
                        {{ $accessories->links() }}
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
                                            style="width: 70px;">STT
                                        </th>
                                        <th class="" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1">Mã phụ tùng</th>
                                        <th class="" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1">Tên phụ tùng</th>
                                        <th class="" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1">Tổng bán 6 tháng</th>
                                        <th class="" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1">Trung bình</th>
                                        <th class="" tabindex="0" aria-controls="category-table"
                                        rowspan="1" colspan="1">Tỉ lệ</th>
                                        <th class="" tabindex="0" aria-controls="category-table"
                                        rowspan="1" colspan="1">Tỉ lệ lũy kế %</th>
                                        <th class="" tabindex="0" aria-controls="category-table"
                                        rowspan="1" colspan="1">Rank</th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    <tr>
                                        <td class="font-weight-bold" colspan=3>TOTAL</td>
                                        <td class="font-weight-bold">{{ $total }}</td>
                                        <td class="font-weight-bold" colspan=3></td>
                                    </tr>
                                    @forelse($accessories as $key => $value)
                                        @if($total > 0)
                                          <?php  $percentage = ($value->quantity)/$total*100 ; ?>
                                        @endif
                                          <?php $accumulateper = $accumulateper+ $percentage ; ?>
                                        @if ($accumulateper <= 85)
                                            <?php $rank = 'A'; ?>
                                        @elseif($accumulateper <=90)
                                            <?php $rank = 'B'; ?>
                                        @elseif($accumulateper <=95)
                                            <?php $rank = 'C'; ?>
                                        @elseif($accumulateper < 100)
                                            <?php $rank = 'D'; ?>
                                        @else
                                            <?php $rank = 'E'; ?>
                                        @endif

                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td class="sorting_1">
                                                {{ ($accessories->currentPage() - 1) * $accessories->perPage() + $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $value->code }}
                                            </td>
                                            <td>
                                                {{ $value->name }}
                                            </td>
                                            <td>
                                                {{ $value->quantity }}
                                            </td>
                                            <td>
                                                {{ $value->quantity / 6 }}
                                            </td>
                                            <td>
                                                {{ $percentage . '%' }}
                                            </td>
                                            <td>
                                                {{ $accumulateper . '%'}}
                                            </td>
                                            <td>
                                                {{ $rank }}
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
                    </div>
                    @if (count($accessories) > 0)
                        {{ $accessories->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="ModalExport" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Xác nhận</h5>
                </div>
                <div class="modal-body">
                    <p>Bạn có muốn xuất file không?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Đóng</button>
                    <button type="button" wire:click.prevent="export()" class="btn btn-primary close-modal"
                        data-dismiss="modal">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
</div>

@section('js')
    <script type="text/javascript">
        // $('#month').val(12);

        /*
        document.addEventListener('DOMContentLoaded', function() {
            $('#month').on('change', function(e) {
                var data = $('#month').val();
                @this.set('month', data);
            });
        }) */

        document.addEventListener('livewire:load', function() {
            // Your JS here.
           // var reportmonth = new Date(); //new Date(date.getFullYear(), date.getMonth(), 1);
           // $('#reportmonth').data("kendoDatePicker").value(reportmonth);

            //reportmonth = $('#reportmonth').data("kendoDatePicker").val();
            $('#reportdate').kendoDatePicker({
                format: "MM/yyyy",
                value: new Date(),
                change: function() {
                    var value = this.value();
                    var month =padWithZero(value.getMonth()+1,2) ;
                    var valueMY = month + '/' + value.getFullYear() ;
                    @this.set('reportdate', valueMY);
                }
            });

        })
        function padWithZero(num, targetLength) {
            return String(num).padStart(targetLength, '0')
            }
    </script>
@endsection
