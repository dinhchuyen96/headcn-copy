<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Danh sách trả góp</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="customerPhone" class="col-1 col-form-label">Thông tin khách hàng</label>
                        <div wire:ignore class="col-3">
                            <select id="customerPhone" name="customerPhone"
                                data-ajax-url="{{ route('customers.getCustomerByPhoneOrName.index') }}"
                                class="custom-select form-control">
                            </select>
                        </div>
                        <label for="contractNumber" class="col-1 col-form-label ">Số hợp đồng</label>
                        <div class="col-3">
                            <input id="contractNumber" name="contractNumber" type="text" class="form-control"
                                placeholder='Số hợp đồng' wire:model.debounce.500ms='contractNumber'>
                        </div>
                        <label for="status" class="col-1 col-form-label ">Trạng thái</label>
                        <div class="col-3">
                            <select wire:model="status" name='status' id="status" class="custom-select select2-box">
                                <option value=''>Chọn trạng thái</option>
                                <option value="1">Đã thanh toán</option>
                                <option value="2">Chưa thanh toán</option>
                            </select>

                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Time" class="col-1 col-form-label ">Ngày mua xe</label>
                        @include('layouts.partials.input._inputDateRanger')
                    </div>
                </form>

                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row btn-group-mt">
                        <div class="col-sm-12">
                            <div id="category-table_filter" class="dataTables_filter">
                                <button type="button" class="btn btn-warning add-new" data-toggle="modal"
                                    data-target="#exportModal" {{ count($data) ? '' : 'disabled' }}>
                                    <i class="fa fa-file-excel-o"></i> Export file
                                </button>
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
                                        <th aria-controls="category-table">STT
                                        </th>
                                        <th class="{{ $key_name == 'contract_number' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table"
                                            wire:click='sorting("contract_number")'>Số hợp đồng</th>
                                        <th class="{{ $key_name == 'customer_name' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table"
                                            wire:click='sorting("customer_name")'>Họ tên KH</th>
                                        <th class="{{ $key_name == 'created_at' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            wire:click='sorting("created_at")'>Ngày mua</th>
                                        <th class="{{ $key_name == 'money' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            wire:click='sorting("money")'>Số tiền</th>
                                        <th class="{{ $key_name == 'company_name' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            wire:click='sorting("company_name")'>Công ty tài chính</th>
                                        <th class="{{ $key_name == 'orders_status' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            wire:click='sorting("orders_status")'>Trạng thái</th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    @forelse ($data as $key => $item)
                                        <tr>
                                            <td>
                                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $item->contract_number }}</td>
                                            <td>{{ $item->customer_name . ' - ' . $item->customer_phone }}</td>
                                            <td>{{ $item->created_at }}
                                            </td>
                                            <td>{{ number_format($item->money) }}</td>
                                            <td>{{ $item->company_name }}</td>
                                            <td>
                                                @if ($item->orders_status == 1)
                                                    <span class="badge badge-success"> Đã thanh toán </span>
                                                @endif
                                                @if ($item->orders_status == 2)
                                                    <span class="badge badge-primary"> Chưa thanh toán </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="text-center text-danger">
                                            <td colspan="10">Không có bản ghi</td>
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
    @include('livewire.common.modal._modalExport')
</div>
@section('js')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            setSelect2Customer();
            $('#status').on('change', function(e) {
                var data = $('#status').select2("val");
                @this.set('status', data);
            });
            setDatePickerUI();
        });
        document.addEventListener('select2Customer', function() {
            setSelect2Customer();
        });

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
            $('#customerPhone').on('change', function(e) {
                var data = $('#customerPhone').select2("val");
                @this.set('customerPhone', data);
            });
        };

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
    </script>
@endsection
