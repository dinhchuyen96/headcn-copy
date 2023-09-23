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
                        <label for="CustomerName" class="col-1 col-form-label ">Mã nhà cung cấp</label>
                        <div class="col-3">
                            <input id="CustomerCode" name="CustomerCode" type="text" class="form-control form-red"
                                placeholder='Nhập mã nhà cung cấp'
                                wire:model.debounce.1000ms='customerCode'>
                        </div>
                        <label for="Time" class="col-1 col-form-label ">Thời gian</label>
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
                                    min={{ $fromDate }} max="{{ date('Y-m-d') }}" wire:model='toDate'>
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
                                        <th class="{{ $key_name == 'code' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("code")'>Mã NCC</th>
                                        <th class="{{ $key_name == 'name' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("name")'>Tên nhà cung cấp</th>
                                        <th class="{{ $key_name2 == 'created_at' ? ($sortingName2 == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting2("created_at")'>Ngày</th>
                                        <th class="{{ $key_name2 == 'note' ? ($sortingName2 == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting2("note")'>Nội Dung</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Dư nợ đầu kỳ</th>
                                        <th class="{{ $key_name2 == 'total_money1' ? ($sortingName2 == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting2("total_money1")'>Giá trị bán
                                        </th>
                                        <th class="{{ $key_name2 == 'total_money2' ? ($sortingName2 == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting2("total_money2")'>Giá trị thanh
                                            toán</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Dư nợ phải trả</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $key => $value)
                                        @if ($value->type_table == 3)
                                            <tr data-parent="" data-index="1" role="row" class="odd">
                                                <td><strong>{{ $value->code }}</strong></td>
                                                <td><strong>{{ $value->name }}</strong></td>
                                                <td></td>
                                                <td></td>
                                                <td><strong>{{ numberFormat($value->ordersUnPaidBefore($fromDateBefore)) }}</strong>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        @elseif($value->type_table==0)
                                            <tr class="text-danger">
                                                <td colspan="7"><strong>Dư nợ cuối phải trả</strong></td>
                                                <td>{{ numberFormat($value->ordersUnPaid($toDateAfter)) }}</td>
                                            </tr>
                                        @else
                                            <tr data-parent="" data-index="1" role="row" class="odd">
                                                <td></td>
                                                <td></td>
                                                <td>{{ reFormatDate($value->created_at) }}</td>
                                                <td>{{ $value->note }}</td>
                                                <td></td>
                                                <td>{{ $value->type_table == 2 ? numberFormat($value->total_money) : '' }}
                                                </td>
                                                <td>{{ $value->type_table == 1 ? numberFormat($value->total_money) : '' }}
                                                </td>
                                                <td></td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr class="text-center text-danger">
                                            <td colspan="8">Không có bản ghi</td>
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
    <!-- modal -->
    @include('livewire.common.modal._modalExport')
</div>
