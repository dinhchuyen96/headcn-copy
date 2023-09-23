<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div wire:loading class="loader"></div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="CustomerName" class="col-1 col-form-label ">Thông tin khách hàng</label>
                        <div class="col-3">
                            <input id="CustomerName" name="CustomerName" type="text" class="form-control"
                                placeholder='Tên khách hàng' wire:model.debounce.1000ms='customerName'
                                {{ $customerID ? 'disabled' : '' }}>
                        </div>
                        <label for="CustomerAddress" class="col-1 col-form-label ">Địa chỉ</label>
                        <div class="col-3">
                            <input id="CustomerAddress" name="CustomerAddress" type="text" class="form-control"
                                placeholder='Địa chỉ' wire:model.debounce.1000ms='customerAddress'
                                {{ $customerID ? 'disabled' : '' }}>
                        </div>
                        <label for="CustomerName" class="col-1 col-form-label ">Mã khách hàng</label>
                        <div class="col-3">
                            <input name="CustomerName" type="text" class="form-control form-red"
                                placeholder='Nhập mã khách hàng'
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
                        <label for="type" class="col-1 col-form-label">Phân loại</label>
                        <div class="col-3">
                            <select name="type" wire:model="type" id="type" class="custom-select select2-box">
                                <option value="0">Tất cả</option>
                                <option value="1">Còn nợ</option>
                                <option value="2">Hết nợ</option>
                            </select>
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
                                <a class="btn btn-warning {{ $customerSelectedId && $type == 1 && count($listSelected) > 0 ? '' : 'disabled ' }}"
                                    target="{{ $customerSelectedId && $type == 1 && count($listSelected) > 0 ? '_blank' : '' }}"
                                    href="{{ $customerSelectedId && $type == 1 && count($listSelected) > 0? route('ketoan.thu.index', ['customerId' => $customerSelectedId]): 'javascript:void(0)' }}"><i
                                        class="fa fa-money"></i> Thu
                                    tiền</a>
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
                                        <th></th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            wire:click='sorting("id")' aria-label="ID: activate to sort column">STT
                                        </th>
                                        <th class="{{ $key_name == 'name' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("name")'>Tên khách hàng</th>
                                        <th class="{{ $key_name == 'phone' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("phone")'>Số điện thoại</th>

                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;"
                                            class="{{ $key_name == 'total_money1' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            wire:click='sorting("total_money1")'>Nợ đầu kỳ</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;"
                                            class="{{ $key_name == 'total_money2' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            wire:click='sorting("total_money2")'>Mua trong kỳ</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;"
                                            class="{{ $key_name == 'total_money3' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            wire:click='sorting("total_money3")'>Đã TT trong kỳ</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;"
                                            class="{{ $key_name == 'total_money4' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            wire:click='sorting("total_money4")'>Nợ còn lại</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Thao tác</th>
                                        <th class="{{ $key_name == 'address' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("address")'>Địa chỉ</th>
                                    </tr>
                                </thead>
                                <tbody>

                                @if (count($data) > 0)
                                    <tr data-parent="" data-index="1" role="row">
                                        <td class="font-weight-bold" colspan="4">Tổng</td>
                                        <td class="font-weight-bold">
                                            {{ numberFormat($data->sum('total_money1')) }}
                                        </td>
                                        <td class="font-weight-bold">
                                            {{ numberFormat($data->sum('total_money2')) }}
                                        </td>
                                        <td class="font-weight-bold">
                                            {{ numberFormat($data->sum('total_money3')) }}
                                        </td>
                                        <td class="font-weight-bold">
                                            {{ numberFormat($data->sum('total_money4')) }}
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                @endif

                                    @forelse ($data as $key => $item)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td>
                                                <input style="margin-left: 30%" type="checkbox"
                                                    value="{{ $item->id }}" name="listSelected"
                                                    wire:model="listSelected" class="check-box-order" />
                                            </td>
                                            <td class="sorting_1">
                                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                            </td>

                                            <td>{!! boldTextSearchV2($item->name, $customerName) !!}</td>

                                            <td>
                                                @if ($customerID)
                                                    {!! boldTextSearchV2($item->phone, $customerPhone) !!}
                                                @else
                                                    {{ $item->phone }}
                                                @endif
                                            </td>

                                            <td>{{ numberFormat($item->total_money1) }}</td>
                                            <td>{{ numberFormat($item->total_money2) }}</td>
                                            <td>{{ numberFormat($item->total_money3) }}</td>
                                            <td>{{ numberFormat($item->total_money4) }}</td>
                                            <td class="text-center">
                                                <a class="btn btn-warning btn-xs m-r-5" data-original-title="Xem"
                                                    wire:click='setShowID({{ $item->id }})' data-toggle="modal"
                                                    data-target="#showModal"><i class="fa fa-eye font-14"></i></a>
                                                @if ($type == 1)
                                                    <a href="{{ route('ketoan.thu.index', ['customerId' => $item->id]) }}"
                                                        target="_blank" class="btn btn-warning btn-xs m-r-5"
                                                        data-toggle="tooltip" data-original-title="Thu tiền">
                                                        <i class="fa fa-money font-14"></i></a>
                                                @endif
                                            </td>
                                            <td>{{ getAddressByUserId($item->id) }}
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
                        {{ $data->appends(Arr::except(Request::query(), 'page'))->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- modal -->
    @include('livewire.common.modal._modalExport')
    @livewire('ketoan.baocao.form-thu-list')
    <!-- end modal  -->
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.livewire.on('setIDCustomer', (id, fromDateBefore, toDateAfter) => {
            @this.emit('setCustomer', id, fromDateBefore, toDateAfter)
        });
        $('#type').on('change', function(e) {
            var data = $('#type').select2("val");
            @this.set('type', data);
        });
    })
</script>
