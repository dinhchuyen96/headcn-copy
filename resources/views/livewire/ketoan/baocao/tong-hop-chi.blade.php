<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Danh sách phải trả</div>
            </div>
            <div wire:loading class="loader"></div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="CustomerName" class="col-1 col-form-label ">Thông tin khách hàng</label>
                        <div class="col-3">
                            <input id="CustomerName" name="CustomerName" type="text" class="form-control"
                                placeholder='Tên nhà cung cấp' wire:model.debounce.1000ms='customerName'
                                {{ $customerID ? 'disabled' : '' }}>
                        </div>
                        <label for="CustomerAddress" class="col-1 col-form-label ">Địa chỉ</label>
                        <div class="col-3">
                            <input id="CustomerAddress" name="CustomerAddress" type="text" class="form-control"
                                placeholder='Địa chỉ' wire:model.debounce.1000ms='customerAddress'
                                {{ $customerID ? 'disabled' : '' }}>
                        </div>
                        <label for="CustomerName" class="col-1 col-form-label ">Mã nhà cung cấp</label>
                        <div class="col-3">
                            <input name="CustomerName" type="text" class="form-control form-red"
                                placeholder='Nhập mã nhà cung cấp'
                                wire:model.debounce.1000ms='customerCode'>
                        </div>
                    </div>
                    <div class="form-group row">

                        <label for="CustomerAddress" class="col-1 col-form-label ">SĐT</label>
                        <div class="col-3">
                            <input name="CustomerAddress" type="number" class="form-control form-red"
                                placeholder='Nhập số điện thoại'
                                wire:model.debounce.1000ms='customerPhone'>
                        </div>
                        <label for="Time" class="col-1 col-form-label ">Thời gian<span class="text-danger">*</span></label>
                        <div class="col-3 row pr-0">
                            <div class="col-5 pr-0">
                                <input type="date" class="form-control input-date-kendo" id="fromDate1"
                                    max="{{ $toDate }}" wire:model='fromDate'>
                            </div>
                            <div class="col-2 justify-content-center align-items-center">
                                <p class="text-center pt-2">～</p>
                            </div>
                            <div class="col-5 pr-0 pl-0">
                                <input type="date" class="form-control input-date-kendo" id="toDate1"
                                    min="{{ $fromDate }}" max="{{ date('Y-m-d') }}" wire:model='toDate'>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row justify-content-center">
                        @include('layouts.partials.button._reset')
                    </div>

                </form>

                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
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
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            wire:click='sorting("id")' aria-label="ID: activate to sort column" width=70px;>STT
                                        </th>
                                        <th class="{{ $key_name == 'code' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("code")'>Mã NCC</th>
                                        <th class="{{ $key_name == 'name' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("name")'>Tên NCC</th>
                                        <th class="{{ $key_name == 'phone' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("phone")'>Số điện thoại</th>
                                        <th class="{{ $key_name == 'address' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("address")'>Địa chỉ</th>
                                        <th class="{{ $key_name == 'total_money1' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("total_money1")'>Số dư nợ đầu
                                            kỳ</th>
                                        <th class="{{ $key_name == 'total_money2' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("total_money2")'>Số tiền mua
                                            hàng trong kỳ</th>
                                        <th class="{{ $key_name == 'total_money3' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("total_money3")'>Đã thanh toán
                                            trong kỳ</th>
                                        <th class="{{ $key_name == 'total_money4' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("total_money4")'>Dư nợ còn lại
                                            phải trả</th>

                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $key => $value)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td class="sorting_1 text-center">
                                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                            </td>
                                            <td>
                                                @if ($customerID)
                                                    {!! boldTextSearchV2($value->code, $customerCode) !!}
                                                @else
                                                    {{ $value->code }}
                                                @endif
                                            </td>
                                            <td>{!! boldTextSearchV2($value->name, $customerName) !!}</td>

                                            <td>
                                                @if ($customerID)
                                                    {!! boldTextSearchV2($value->phone, $customerPhone) !!}
                                                @else
                                                    {{ $value->phone }}
                                                @endif
                                            </td>
                                            <td>{!! boldTextSearchV2($value->address, $customerAddress) !!}</td>
                                            <td>{{ numberFormat($value->total_money1) }}</td>
                                            <td>{{ numberFormat($value->total_money2) }}</td>
                                            <td>{{ numberFormat($value->total_money3) }}</td>
                                            <td>{{ numberFormat($value->total_money4) }}</td>
                                            <td class="text-center">
                                                <a class="btn btn-warning btn-xs m-r-5" data-original-title="Xem"
                                                    wire:click='setShowID({{ $value->id }})' data-toggle="modal"
                                                    data-target="#showModal"><i class="fa fa-eye font-14"></i></a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="text-center text-danger">
                                            <td colspan="9">Không có bản ghi</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if (count($data) > 0)
                        {{ $data->appends(Arr::except(Request::query(), 'page'))->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- modal -->
    @include('livewire.common.modal._modalExport')
    @livewire('ketoan.baocao.form-chi-list')
    <!-- end modal  -->
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.livewire.on('setIDCustomer', (id, fromDateBefore, toDateAfter) => {
            @this.emit('setCustomer', id, fromDateBefore, toDateAfter)
        });
    })
</script>
