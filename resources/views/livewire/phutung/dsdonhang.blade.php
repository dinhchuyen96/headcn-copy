<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Bán phụ tùng</div>
            </div>
            <div class="ibox-body">
                <div id='advance-search-box'>
                    <form>
                        <div class="form-group row">
                            <label for="CustomerName" class="col-1 col-form-label ">Họ và tên KH</label>
                            <div class="col-3">
                                <input id="CustomerName" name="CustomerName" type="text" class="form-control"
                                    wire:model.debounce.1000ms="searchName" autocomplete="off">
                            </div>
                            <label for="searchPartNo" class="col-1 col-form-label ">Mã Phụ tùng</label>
                            <div class="col-3">
                                <input id="searchPartNo" name="searchPartNo" type="text" class="form-control"
                                    wire:model.debounce.1000ms="searchPartNo" autocomplete="off">
                            </div>
                            <label for="Type" class="col-1 col-form-label ">Phân loại</label>
                            <div class="col-3">
                                <select name="Type" id="Type" class="custom-select select2-box"
                                    wire:model.debounce.1000ms="searchType">
                                    <option value="0">--Chọn--</option>
                                    <option value="1">Bán buôn</option>
                                    <option value="2">Bán lẻ</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="PayStatus" class="col-1 col-form-label ">Trạng thái thanh toán</label>
                            <div class="col-3">
                                <select name="PayStatus" id="PayStatus" class="custom-select select2-box"
                                    wire:model.debounce.1000ms="searchStatus">
                                    <option value="0">--Chọn--</option>
                                    <option value="1">Đã thanh toán</option>
                                    <option value="2">Chưa thanh toán</option>
                                    <option value="4">Đã hủy</option>
                                </select>
                            </div>
                            <label for="Time" class="col-1 col-form-label ">Thời gian</label>
                            @include('layouts.partials.input._inputDateRanger')

                            <label for="" class="col-1 col-form-label "></label>
                            <div class="col-3 text-left virtual">
                                <input type='checkbox' id='chkIsVirtual' style="margin: 14px 0px"
                                    wire:model="chkIsVirtual">
                                Đơn ảo
                                <input type='checkbox' id='chkIsReal' style="margin: 14px 0px" wire:model="chkIsReal">
                                Đơn
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
                        <div class="col-sm-12 col-md-4">
                            <div style='display:none' class="dataTables_length" id="category-table_length"><label>Hiển
                                    thị <select name="category-table_length" aria-controls="category-table"
                                        class="form-control form-control-sm" wire:model="perPage">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select></label></div>
                        </div>
                        <div class="col-sm-12 col-md-8">
                            <div id="category-table_filter" class="dataTables_filter">
                                <button class="btn btn-outline-primary" data-target="#ModalReturn" data-toggle="modal"
                                    type="button" id='btnReturn'>
                                    <i class="fa fa-undo"></i>
                                    <span>TRẢ LẠI</span>
                                </button>
                                <button class="btn btn-outline-primary" type="button" id='btnRetailOrder'>
                                    <a href="{{ route('phutung.banle.index') }}">
                                        <i class="fa fa-plus-circle"></i>
                                        <span>BÁN LẺ</span>
                                    </a>
                                </button>
                                <button class="btn btn-outline-primary" type="button" id='btnWholeSaleOrder'>
                                    <a href="{{ route('phutung.banbuon.index') }}">
                                        <i class="fa fa-plus-circle"></i>
                                        <span>BÁN BUÔN</span>
                                    </a>
                                </button>
                                <button data-target="#modal-form-export" data-toggle="modal" type="button"
                                    class="btn btn-warning add-new" {{ count($data) ? '' : 'disabled' }}><i
                                        class="fa fa-file-excel-o"></i> Export file</button>
                                <a class="btn btn-warning {{ $customerSelectedId && $orderSelectedId && count($listSelected) > 0 ? '' : 'disabled ' }}"
                                    target="{{ $customerSelectedId && $orderSelectedId && count($listSelected) > 0 ? '_blank' : '' }}"
                                    href="{{ $customerSelectedId && $orderSelectedId && count($listSelected) > 0 ? route('ketoan.thu.index', ['customerId' => $customerSelectedId, 'orderId' => $orderSelectedId]) : 'javascript:void(0)' }}"><i
                                        class="fa fa-money"></i> Thu
                                    tiền</a>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 table-responsive">
                            <table class="table table-striped table-bordered dataTable no-footer" id="category-table"
                                cellspacing="0" width="100%" role="grid" aria-describedby="category-table_info"
                                style="width: 100%;overflow-x: scroll;white-space: nowrap;">
                                <thead>
                                    <tr role="row">
                                        <th></th>
                                        <th class="id_column" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" aria-sort="ascending"
                                            aria-label="ID: activate to sort column descending">STT
                                        </th>
                                        <th class="id_column" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" aria-sort="ascending"
                                            aria-label="ID: activate to sort column descending">Order ID
                                        </th>

                                        <th wire:click="sorting2('customers.name')"
                                            class="@if ($this->key_name2 == 'customers.name') {{ $sortingName2 == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="">Tên khách hàng</th>


                                        <th>Mã Phụ tùng</th>
                                        <th>Tên Phụ tùng</th>
                                        <th>SL</th>
                                        <th wire:click="sorting2('code')"
                                            class="@if ($this->key_name2 == 'code') {{ $sortingName2 == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Tổng tiền</th>
                                        <th wire:click="sorting2('orders.status')"
                                            class="@if ($this->key_name2 == 'orders.status') {{ $sortingName2 == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="">Trạng thái</th>
                                        <th wire:click="sorting2('orders.type')"
                                            class="@if ($this->key_name2 == 'orders.type') {{ $sortingName2 == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Phân loại</th>
                                        <th wire:click="sorting2('created_at')"
                                            class="@if ($this->key_name2 == 'created_at') {{ $sortingName2 == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Ngày tạo</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width:200px;">Thao tác</th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    @php($current_part_no = '')
                                    @forelse ($data as $row)
                                        @if ($loop->index > 0 && $current_part_no != $row->part_no && !$loop->last)
                                            @php($current_part_no = $row->part_no)
                                        @endif
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td>
                                                @if ($row->status != 1)
                                                    <input style="margin-left: 30%" type="checkbox"
                                                        value="{{ $row->customer_id . '_' . $row->id }}"
                                                        name="listSelected" wire:model="listSelected"
                                                        class="check-box-order" />
                                                @endif
                                            </td>
                                            <td class="sorting_1">
                                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $row->id }}</td>
                                            <td>{{ isset($row->customer->name) ? $row->customer->name : '' }}</td>


                                            <td>{{ isset($row->part_no) ? $row->part_no : '' }}</td>
                                            <td>{{ isset($row->part_name) ? $row->part_name : '' }}</td>
                                            <td>{{ isset($row->qty) ? $row->qty : 0 }}</td>
                                            <td>{{ isset($row->amount) ? numberFormat($row->amount) : 0 }}</td>
                                            <td>
                                                @if ($row->status == 1)
                                                    <span class="badge badge-success"> Đã thanh toán </span>
                                                @else
                                                    <span class="badge badge-primary"> Chưa thanh toán </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($row->type == 1)
                                                    <span>Bán buôn</span>
                                                @elseif($row->type == 2)
                                                    <span>Bán lẻ</span>
                                                @else
                                                    <span>Hóa đơn nhập</span>
                                                @endif
                                            </td>
                                            <td>{{ reFormatDate($row->created_at, 'd/m/Y') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route($row->route(), ['id' => $row->id, 'show' => 'true']) }}"
                                                    class="btn btn-info btn-xs m-r-5" data-toggle="tooltip"
                                                    data-original-title="Xem"><i class="fa fa-eye font-14"></i></a>

                                                @if ($row->status != 1)
                                                    <a href="{{ route($row->route(), ['id' => $row->id]) }}"
                                                        class="btn btn-primary btn-xs m-r-5" data-toggle="tooltip"
                                                        data-original-title="Sửa"><i
                                                            class="fa fa-pencil font-14"></i></a>


                                                    <a href="#" data-toggle="modal"
                                                        @if ($row->status == 2) data-target="#deleteModal" wire:click='deleteId({{ $row->id }})' @else wire:click='showMessage()' @endif
                                                        class="btn btn-danger delete-category btn-xs m-r-5"
                                                        data-toggle="tooltip" data-original-title="Xóa">
                                                        <i class="fa fa-trash font-14"></i></a>
                                                @endif

                                                @if ($row->status != 1)
                                                    <a href="{{ route('ketoan.thu.index', ['customerId' => $row->customer_id, 'orderId' => $row->id]) }}"
                                                        target="_blank" class="btn btn-warning btn-xs m-r-5"
                                                        data-toggle="tooltip" data-original-title="Thu tiền">
                                                        <i class="fa fa-money font-14"></i></a>
                                                @endif

                                            </td>
                                        </tr>

                                        @if ($loop->last)
                                            @include(
                                                'livewire.phutung.total-dsdonhang',
                                                compact('data', 'current_part_no', 'totalQty', 'totalAmount')
                                            )
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="13" class="text-center text-danger">Không có bản ghi nào.</td>
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


        <!--- modal return -->
        <div wire:ignore.self class="modal fade" id="ModalReturn" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <form>
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">

                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class='col-sm-12'>
                                    <label for='customerPhone'>Khách hàng </label>       
                                    
                                    <div wire:ignore>
                                        <select name='customerPhone' id="customerPhone"
                                            data-ajax-url="{{ route('customers.getCustomerByPhoneOrNameWithId.index') }}"
                                            class="custom-select">
                                        </select>
                                    </div>
                                        {{-- <select id='customerPhone' name="customerPhone" style='width:100% !important'
                                            wire:model.lazy='returncustomerid' class="custom-select select2-box-ajax col-sm-12"
                                            data-ajax-url="{{ route('customers.getCustomerByPhoneOrName.index') }}">
                                            <option value=''>Chọn khách hàng</option>
                                            @if (isset($listcustomer))
                                                @foreach ($listcustomer as $item)
                                                    <option value='{{ $item->id }}'
                                                        {{ isset($returncustomerid) && $item->id == $returncustomerid ? 'selected' : '' }}>
                                                        {{ $item->name . '-' . $item->phone }}</option>
                                                @endforeach
                                            @endif
                                        </select> --}}
                                    @error('returncustomerid')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>
                            </div>
                            <div class="form-group">
                                <div class='col-sm-12'>
                                    <label for="returnpartid">Mã phụ tùng <br></label>
                                    <select id="returnpartid" style='width:100% !important'
                                        wire:model.lazy='returnpartid' class="custom-select select2-box col-sm-12">
                                        <option value=''>Chọn mã phụ tùng</option>
                                        @if (isset($listreturnpart) && count($listreturnpart) > 0)
                                            @foreach ($listreturnpart as $itempart)
                                                <option
                                                    {{ isset($returnpartid) && $returnpartid == $itempart->id ? 'selected' : '' }}
                                                    value="{{ $itempart->id }}">
                                                    {{ $itempart->code . '-' . $itempart->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('returnpartid')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>                    
                            <div class="form-group">
                                <div class="container">
                                    <div class="row">
                                            <div class="col-sm-4">
                                                @if ($PONumber)
                                                    <span> Số PO của phụ tùng: {{ $PONumber }}</span>                                
                                                @endif
                                                @if ($buyQty)
                                                    <span> Số lượng đã mua: {{ $buyQty }}</span>
                                                @endif
            
                                            </div>
                                            <div class='col-sm-8'>
                                                @if ($returnprice)
                                                    <span> Giá mua của phụ tùng: {{  numberFormat($returnprice) . ' vnd' }}</span>
                                                @endif
                                            </div>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                              

                                <div class='col-sm-12'>
                                    <label for='returnqty'>Số lượng trả lại<br></label>
                                    <input type='text' wire:model.lazy='returnqty' id='returnqty' class='col-sm-12'
                                        placeholder='Nhập số lượng' />
                                    @error('returnqty')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <div class='col-sm-12'>
                                    <label for='returndescription'>Ghi chú<br></label>
                                    <input type='text' wire:model='returndescription' id='returndescription'
                                        class='col-sm-12' placeholder='Ghi chú' />
                                    @error('returndescription')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Đóng</button>
                            <button type="button" wire:click.prevent="doreturnpart()"
                                class="btn btn-primary close-modal" {{ (!$validCheckReturn) ? 'disabled' : '' }} data-dismiss="modal">Đồng ý</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!--- modal return -->
    </div>
    <script>



        document.addEventListener('livewire:load', function() {
            
        })

        window.livewire.on('close-modal-delete', () => {
            document.getElementById('close-modal-delete').click();
        })

        function setSelect2Customer() {
            let ajaxUrl = $('#customerPhone').data("ajaxUrl");
            $('#customerPhone').select2({
                ajax: {
                    url: ajaxUrl,
                    data: function(params) {
                        var query = {
                            search: params.term,
                        }
                        return query;
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                },
                placeholder: 'Nhập tên hoặc SĐT để tìm kiếm',
            });
        };

        document.addEventListener('select2Customer', function() {
            setSelect2Customer();
        });

        document.addEventListener('DOMContentLoaded', function() {
            $('.select2-box-ajax').select2({
                minimumInputLength: 3,
                ajax: {
                    url: '{{ route("api.cities.search") }}',
                    dataType: 'json',
                },
            });

            setSelect2Customer()

            


            setDatePickerUI();
            $('#PayStatus').on('change', function(e) {
                var data = $('#PayStatus').select2("val");
                @this.set('searchStatus', data);
            });
            $('#Type').on('change', function(e) {
                var data = $('#Type').select2("val");
                @this.set('searchType', data);
            });

            $('#returncustomerid').on('change', function(e) {
                var data = $('#returncustomerid').select2("val");
                @this.set('returncustomerid', data);
            });
            $('#returnpartid').on('change', function(e) {
                var data = $('#returnpartid').select2("val");
                console.log(data);
                @this.set('returnpartid', data);
            });
            $('#returnwarehouseid').on('change', function(e) {
                var data = $('#returnwarehouseid').select2("val");
                @this.set('returnwarehouseid', data);
            });
            $('#returnpositionid').on('change', function(e) {
                var data = $('#returnpositionid').select2("val");
                @this.set('returnpositionid', data);
            });

            $('#returnqty').on('change', function(e) {
                Livewire.emit('validateQuantity');
            })

            $('#customerPhone').on('change', function(e) {
                var data = $('#customerPhone').select2("val");
                console.log(data);
                @this.set('returncustomerid', data);
            });


            //handle click advance search
            $('#btnadvancesearch').click(function() {
                $('#simple-search-box').hide();
                $('#advance-search-box').show();
                @this.set('showAdvancesearch', 1);
                @this.set('keyword', '');
            });
            //set ui date
            function setDatePickerUI() {
                $("#fromDate").kendoDatePicker({
                    max: new Date(),
                    value: new Date(),
                    format: 'dd/MM/yyyy',
                    change: function() {
                        if (this.value() != null) {
                            window.livewire.emit('setfromDate', {
                                ['fromDate']: this.value() ? this.value().toLocaleDateString(
                                    'en-US') : null
                            });
                        }
                    }
                });
                $("#toDate").kendoDatePicker({
                    max: new Date(),
                    value: new Date(),
                    format: 'dd/MM/yyyy',
                    change: function() {
                        if (this.value() != null) {
                            window.livewire.emit('settoDate', {
                                ['toDate']: this.value() ? this.value().toLocaleDateString(
                                    'en-US') : null
                            });
                        }
                    }
                });
            };
            //end setui date
        })
    </script>
    <style>
        .modal-dialog {
            width: 98%;
            height: 92%;
            padding: 0;
        }

        .modal-content {
            height: 99%;
        }

    </style>
</div>
