<div>
    <div class="page-heading">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fa fa-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">BÁO CÁO BÁN HÀNG PT</li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-body">
                @if($showAdvancesearch==0)
                <div id='advance-search-box' style='display:none' >
                @else
                <div id='advance-search-box' style='display:block' >
                @endif
                    <form>
                        <div class="form-group row">
                            <label for="Type" class="col-2 col-form-label ">Phân loại</label>
                            <div class="col-4">
                                <select name="Type" id="Type" class="custom-select select2-box"
                                    wire:model.debounce.1000ms="searchType">
                                    <option value="0">--Chọn--</option>
                                    <option value="1">Bán buôn</option>
                                    <option value="2">Bán lẻ</option>
                                </select>
                            </div>
                            <label for="searchPartNo" class="col-2 col-form-label ">Mã Phụ tùng</label>
                            <div class="col-4">
                                <input id="searchPartNo" name="searchPartNo" type="text" class="form-control"
                                    wire:model.debounce.1000ms="searchPartNo" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="PayStatus" class="col-2 col-form-label ">Trạng thái thanh toán</label>
                            <div class="col-4">
                                <select name="PayStatus" id="PayStatus" class="custom-select select2-box"
                                    wire:model.debounce.1000ms="searchStatus">
                                    <option value="0">--Chọn--</option>
                                    <option value="1">Đã thanh toán</option>
                                    <option value="2">Chưa thanh toán</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="Time" class="col-2 col-form-label ">Thời gian</label>
                            @include('layouts.partials.input._inputDateRanger')

                            <div class="col-6 text-right virtual">
                                <input type='checkbox' id='chkIsVirtual' style="margin: 14px 0px" wire:model="chkIsVirtual">
                                Đơn ảo
                                <input type='checkbox' id='chkIsReal' style="margin: 14px 0px" wire:model="chkIsReal"> Đơn
                                thực
                            </div>

                        </div>
                        <div class="form-group row justify-content-center">
                            @include('layouts.partials.button._reset')
                        </div>

                    </form>
                </div>

                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div style='display:none' class="dataTables_length" id="category-table_length"><label>Hiển
                                    thị <select name="category-table_length" aria-controls="category-table"
                                        class="form-control form-control-sm" wire:model="perPage">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select></label></div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div id="category-table_filter" class="dataTables_filter">

                                <button data-target="#modal-form-export" data-toggle="modal" type="button"
                                    class="btn btn-warning add-new" {{ count($data) ? '' : 'disabled' }}><i
                                        class="fa fa-file-excel-o"></i> Export file</button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 table-responsive">
                            <table class="table table-striped table-bordered dataTable no-footer"
                                id="category-table" cellspacing="0" role="grid"
                                aria-describedby="category-table_info" style="width: 100%;">
                                <thead>
                                    <tr role="row">
                                        <th class="id_column" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" aria-sort="ascending"
                                            aria-label="ID: activate to sort column descending" style='5%'>STT
                                        </th>
                                        <th style='width:20%'>Mã Phụ tùng</th>
                                        <th style='width:30%'>Tên Phụ tùng</th>
                                        <th style='width:5%'>SL</th>
                                        <th style='width:10%'>Đơn giá</th>
                                        <th style='width:20%'>
                                            Doanh thu</th>
                                        <th style='width:20%'>Lợi nhuận</th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    @php ($current_part_no = '')
                                    @forelse ($data as $row)

                                        @if ($loop->index > 0 && $current_part_no != $row->part_no && !$loop->last)
                                            @php ($current_part_no = $row->part_no)
                                        @endif
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td class="sorting_1">
                                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                            </td>



                                            <td>{{ isset($row->part_no) ? $row->part_no : '' }}</td>
                                            <td>{{ isset($row->part_name) ? $row->part_name : '' }}</td>
                                            <td>{{ isset($row->qty) ? $row->qty : 0  }}</td>
                                            <td>{{ isset($row->actual_price) ? numberFormat($row->actual_price) : 0  }}</td>
                                            <td>{{ isset($row->amount) ? numberFormat($row->amount) : 0 }}</td>
                                            <td>{{ isset($row->amount) ? numberFormat($row->amount-$row->cost_mount) : 0 }}</td>
                                        </tr>
                                        {{ $totalQty += (isset($row->qty) ? $row->qty : 0) }}
                                        {{ $totalAmount += (isset($row->amount) ? $row->amount : 0) }}
                                        {{ $totalRevenue += ($row->amount-$row->cost_mount) }}
                                        @if ($loop->last)
                                            @include ('livewire.phutung.total-baocaolailo', compact('data', 'current_part_no', 'totalQty','totalAmount','totalRevenue'))
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-danger">Không có bản ghi nào.</td>
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
                @include('layouts.partials.button._exportForm')
                @include('livewire.common.modal._modalDelete')
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('livewire:load', function() {
            // Your JS here.
            var fromDate = new Date(); //new Date(date.getFullYear(), date.getMonth(), 1);
            $('#fromDate').data("kendoDatePicker").value(fromDate);
            var todayDate = new Date();
            $('#toDate').data("kendoDatePicker").value(toDate);

            datafrom = $('#fromDate').data("kendoDatePicker").val();
            datato = $('#toDate').data("kendoDatePicker").val();
            @this.set('searchFromDate', datafrom);
            @this.set('searchToDate', datato);

            $('#categroy-table').DataTable( {
                "scrollX": true
            } );
        })

        window.livewire.on('close-modal-delete', () => {
            document.getElementById('close-modal-delete').click();
        })
        document.addEventListener('DOMContentLoaded', function() {
            $('#PayStatus').on('change', function(e) {
                var data = $('#PayStatus').select2("val");
                @this.set('searchStatus', data);
            });
            $('#Type').on('change', function(e) {
                var data = $('#Type').select2("val");
                @this.set('searchType', data);
            });

            //handle click advance search
            $('#btnadvancesearch').click(function(){
                $('#simple-search-box').hide();
                $('#advance-search-box').show();
                @this.set('showAdvancesearch',1);
                @this.set('keyword','');
            });
        })


    </script>
</div>
