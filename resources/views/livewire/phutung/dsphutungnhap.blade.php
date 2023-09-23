<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">DS phụ tùng nhập</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="AccessaryName" class="col-2 col-form-label">Tên phụ tùng</label>
                        <div class="col-4">
                            <input wire:model.lazy="searchName" type="text" class="form-control">
                        </div>
                        <label for="AccessaryNumber" class="col-2 col-form-label ">Mã phụ tùng</label>
                        <div class="col-4">
                            <input wire:model="searchCode" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="SupplyName" class="col-2 col-form-label">Nhà CC</label>
                        <div class="col-4">
                            <select name="SupplyName" id="SupplyName" class="custom-select select2-box"
                                wire:model="searchSupplierName">
                                <option value="0">---Chọn---</option>
                                @foreach ($querySupplier as $qrs)
                                    <option value="{{ $qrs->name }}">{{ $qrs->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label for="Time" class="col-2 col-form-label ">Thời gian</label>
                        @include('layouts.partials.input._inputDateRanger')
                    </div>
                    <div class="form-group row justify-content-center">
                        @include('layouts.partials.button._reset')
                    </div>
                </form>
                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div style='display:none' class="dataTables_length" id="category-table_length"><label>Hiển
                                    thị <select wire:model="perPage" name="category-table_length"
                                        aria-controls="category-table" class="form-control form-control-sm">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select></label></div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div id="category-table_filter" class="dataTables_filter">

                                <button class="btn btn-outline-primary" type="button" id='btnWholeSaleOrder'>
                                    <a href="{{ route('phutung.nhapphutung.index') }}">
                                        <i class="fa fa-plus-circle"></i>
                                        <span>THÊM ĐƠN NHẬP</span>
                                    </a>
                                </button>

                                <button data-target="#modal-form-export" data-toggle="modal" type="button"
                                    class="btn btn-primary" {{ count($data) ? '' : 'disabled' }}><i
                                        class="fa fa-file-excel-o"></i> Export file</button>

                            </div>
                        </div>
                    </div>
                    <!--
                    @if (count($data) > 0)
{{ $data->links() }}
@endif
                    -->

                    <!---order list header -->
                    <div class="row">
                        <div class="col-sm-12 table-responsive">
                            <table class="table table-striped table-bordered dataTable no-footer" id="orders-table"
                                cellspacing="0" width="100%" role="grid" aria-describedby="category-table_info"
                                style="width: 100%;display:block;overflow-x: scroll;white-space: nowrap;">
                                <thead>
                                    <tr role="row">
                                        <th class="id_column" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1">ID
                                        </th>
                                        <th class="order_id" tabindex="0" aria-controls="category-table"
                                            style='width:20%' rowspan="1" colspan="1">
                                            Order ID</th>
                                        <th class="order_id" tabindex="0" aria-controls="category-table"
                                            style='width:20%' rowspan="1" colspan="1">
                                            Số phiếu
                                        </th>
                                        <th class="order_id" tabindex="0" aria-controls="category-table"
                                            style='width:20%' rowspan="1" colspan="1">
                                            PO Number
                                        </th>
                                        <th class="order_id" tabindex="0" aria-controls="category-table"
                                            style='width:20%' rowspan="1" colspan="1">
                                            Nhà CC
                                        </th>

                                        <th class="order_id" tabindex="0" aria-controls="category-table"
                                            style='width:20%' rowspan="1" colspan="1">
                                            Thực nhận
                                        </th>
                                        <th class="order_id" tabindex="0" aria-controls="category-table"
                                            style='width:20%' rowspan="1" colspan="1">
                                            Tổng tiền
                                        </th>
                                        <th class="order_id" tabindex="0" aria-controls="category-table"
                                            style='width:20%' rowspan="1" colspan="1">
                                            Ngày nhận
                                        </th>
                                        <th class="order_id" tabindex="0" aria-controls="category-table"
                                            style='width:20%' rowspan="1" colspan="1">
                                            &nbsp;
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($dataorders) && count($dataorders) > 0)
                                        @foreach ($dataorders as $key => $value)
                                            <tr data-parent="" data-index="1" role="row"
                                                class="{{ $selectedorderid == $value->order_id ? 'tr-order selected' : 'tr-order' }}"
                                                data-url="{{ $value->order_id }}">
                                                <td> {{ $loop->iteration }} </td>
                                                <td>{{ $value->order_id }} </td>
                                                <td>{{ $value->bill_number }} </td>
                                                <td>{{ $value->order_number }} </td>
                                                <td>{{ $value->spname }} </td>
                                                <td>{{ $value->receipt_qty }} </td>
                                                <td>{{ $value->amount }} </td>
                                                <td>{{ $value->receipt_date }} </td>
                                                <td>
                                                    <button type="button" data-toggle="modal"
                                                        data-target="#deleteOrderModal"
                                                        data-order-id="{{ $value->order_id }}"
                                                        class="btn btn-danger delete-order btn-xs m-r-5"
                                                        data-original-title="Xóa"><i
                                                            class="fa fa-trash font-14"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if (count($dataorders) > 0)
                        {{ $dataorders->links() }}
                    @endif
                    <!---end order list header -->
                    <div class='row'>
                        <div class="col-sm-12">
                            <span>CHI TIẾT ĐƠN HÀNG</span>
                        </div>
                    </div>
                    <!--order list detail -->

                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                id="category-table" cellspacing="0" width="100%" role="grid"
                                aria-describedby="category-table_info"
                                style="width: 100%;overflow-x: scroll;white-space: nowrap;">
                                <thead>
                                    <tr role="row">
                                        <th class="id_column" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1">ID
                                        </th>

                                        <th wire:click="sorting('code')"
                                            class="@if ($this->key_name == 'code') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Mã phụ tùng</th>
                                        <th wire:click="sorting('acname')"
                                            class="@if ($this->key_name == 'acname') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Tên phụ tùng</th>
                                        <th wire:click="sorting('quantity')"
                                            class="@if ($this->key_name == 'quantity') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Số lượng</th>
                                        <th wire:click="sorting('listed_price')"
                                            class="@if ($this->key_name == 'listed_price') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Đơn giá</th>
                                        <th>Thành tiền</th>
                                        <th wire:click="sorting('spname')"
                                            class="@if ($this->key_name == 'spname') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">NCC</th>
                                        <th wire:click="sorting('order_details.created_at')"
                                            class="@if ($this->key_name == 'order_details.created_at') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Ngày nhập</th>

                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 100.5px;">Thao tác</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <div wire:loading class="loader"></div>
                                    <?php
                                    $i = 1;
                                    ?>
                                    @php($current_part_no = '')
                                    @forelse ($data as $key => $dt)
                                        @if ($loop->index > 0 && $current_part_no != $dt->code && !$loop->last)
                                            @php($current_part_no = $dt->code)
                                        @endif

                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td class="sorting_1">
                                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                            </td>

                                            <td>{{ $dt->code }}</td>
                                            <td>{{ $dt->acname }}</td>
                                            <td>{{ $dt->quantity }}</td>
                                            <td>{{ numberFormat($dt->price) }}</td>
                                            <td>{{ numberFormat($dt->amount) }}</td>
                                            <td>{{ $dt->spname }}</td>
                                            <td>{{ reFormatDate($dt->buy_date, 'd/m/Y') }}</td>


                                            <td class="text-center">
                                                {{-- <a href="{{ route('phutung.nhapphutung.index', ['id' => $dt->order_id, 'show' => 'true']) }}"
                                                    class="btn btn-warning btn-xs m-r-5" data-toggle="tooltip"
                                                    data-original-title="Xem"><i class="fa fa-eye font-14"></i></a>
                                                <a href="{{ route('phutung.nhapphutung.index', ['id' => $dt->order_id]) }}"
                                                    class="btn btn-primary btn-xs m-r-5" data-toggle="tooltip"
                                                    data-original-title="Sửa"><i class="fa fa-pencil font-14"></i></a> --}}
                                                <span data-toggle="tooltip" title="Xoá">
                                                    <button type="button" data-toggle="modal" data-target="#deleteModal"
                                                        wire:click="deleteId({{ $dt->id }})"
                                                        class="btn btn-danger delete-category btn-xs m-r-5"
                                                        data-original-title="Xóa"><i
                                                            class="fa fa-trash font-14"></i></button>
                                                </span>
                                                <div wire:ignore.self class="modal fade" id="deleteModal"
                                                    tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-backdrop fade in" style="height: 100%;"></div>
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Xác
                                                                    nhận xóa</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Bạn có chắc chắn muốn xóa</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button"
                                                                    class="btn btn-secondary close-btn"
                                                                    data-dismiss="modal">Đóng</button>
                                                                <button type="button" wire:click.prevent="delete()"
                                                                    class="btn btn-danger close-modal"
                                                                    data-dismiss="modal">Xóa</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        @if ($loop->last)
                                            @include ('livewire.phutung.total-dsphutungnhap',
                                                compact('data', 'current_part_no', 'totalQty', 'totalAmount'))
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center text-danger">Không có bản ghi nào</td>
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

        <div wire:ignore.self class="modal fade" id="deleteOrderModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-backdrop fade in" style="height: 100%;"></div>
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Xác nhận xóa</h5>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn xóa</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Đóng</button>
                        <button type="button" wire:click.prevent="deleteOrder()" class="btn btn-danger close-modal"
                            data-dismiss="modal">Xóa</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.partials.button._exportForm')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $(document).on('click', '.delete-order', function() {
                var orderId = $(this).attr('data-order-id');
                @this.set('deleteOrderId', orderId);
            });
        })
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


        })


        window.livewire.on('close-modal-delete', () => {
            document.getElementById('close-modal-delete').click();
        })
        document.addEventListener('DOMContentLoaded', function() {
            $('#SupplyName').on('change', function(e) {
                var data = $('#SupplyName').select2("val");
                @this.set('searchSupplierName', data);
            });

            $('#orders-table').on("click", "tr.tr-order", function() {
                //alert($(this).data("url"));
                var selectedorderid = $(this).data("url");
                $('.selected').removeClass('selected');
                $(this).addClass("selected");
                @this.emit('setSelectOrder', selectedorderid);

            });
        });
    </script>
    <style>
        .selected {
            background-color: #3498DB;
        }
    </style>
</div>
