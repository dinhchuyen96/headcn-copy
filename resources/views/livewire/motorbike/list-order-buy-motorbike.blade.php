<div class="page-content fade-in-up">
    <div class="ibox">

        <div class="ibox-body">
            <form>
                <div class="form-group row">
                    <label for="CustomerName" class="col-2 col-form-label ">Họ và tên KH</label>
                    <div class="col-4">
                        <input id="CustomerName" name="CustomerName" type="text" class="form-control"
                            wire:model.debounce.1000ms="searchName" autocomplete="off">
                    </div>
                    <label for="engineno" class="col-2 col-form-label ">Số máy</label>
                    <div class="col-4">
                        <input id="engineno" name="engineno" wire:model.debounce.1000ms="engineno" autocomplete="off"
                            type="text" class="form-control" value="">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="type" class="col-2 col-form-label ">Phân loại</label>
                    <div class="col-4">
                        <select name="Type" id="Type" class="custom-select select2-box" wire:model="searchType"
                            autocomplete="off">
                            <option value="">--Chọn phân loại--</option>
                            <option value="1">Bán buôn</option>
                            <option value="2">Bán lẻ</option>
                        </select>
                    </div>
                    <label for="searchStatus" class="col-2 col-form-label ">Trạng thái thanh toán</label>
                    <div class="col-4">
                        <select name="searchStatus" id="searchStatus" class="custom-select select2-box"
                            wire:model="searchStatus" autocomplete="off">
                            <option value="">--Chọn trạng thái--</option>
                            <option value="1">Đã thanh toán</option>
                            <option value="2">Chưa thanh toán</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="Time" class="col-2 col-form-label ">Thời gian</label>
                    @include('layouts.partials.input._inputDateRangerNow')
                    <div class="col-6 text-right virtual">
                        <input type='checkbox' id='isVirtual' style="margin: 14px 0px" wire:model="isVirtual"> Đơn ảo
                        <input type='checkbox' id='isReal' style="margin: 14px 0px" wire:model="isReal"> Đơn thực
                    </div>
                </div>
                <div class="form-group row justify-content-center">
                    @include('layouts.partials.button._reset')
                </div>
            </form>
            <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div id="category-table_filter" class="dataTables_filter">
                            <button name="submit" type="submit" class="btn btn-warning add-new" data-target="#exportModal"
                                data-toggle="modal" type="button" {{ count($data) ? '' : 'disabled' }}><i
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
                        <table class="table table-striped table-bordered dataTable no-footer"
                               id="category-table" cellspacing="0" width="100%" role="grid"
                            aria-describedby="category-table_info"
                            style="width: 100%;display:block;overflow-x: scroll;white-space: nowrap;">
                            <thead>
                                <tr role="row">
                                    <th></th>
                                    <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        wire:click='sorting("id")' aria-label="ID: activate to sort column"
                                        style="width: 15px;">STT
                                    </th>
                                    <th>Số khung</th>
                                    <th>Số máy</th>
                                    <th class="{{ $key_name == 'name' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 164.5px;" wire:click="sorting('name')">Tên KH</th>
                                    <th class="{{ $key_name == 'address' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 164.5px;" wire:click="sorting('address')">Địa chỉ</th>
                                    <th class="{{ $key_name == 'total_money_original' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 164.5px;" wire:click="sorting('total_money_original')">Giá bán
                                    </th>
                                    <th class="{{ $key_name == 'contract_number' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 164.5px;" wire:click="sorting('contract_number')">Số HĐ Trả góp
                                    </th>
                                    <th class="{{ $key_name == 'installment_money' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 164.5px;" wire:click="sorting('installment_money')">Tiền HĐ trả
                                        góp</th>

                                    <th class="{{ $key_name == 'total_money' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 164.5px;" wire:click="sorting('total_money')">Còn nợ</th>

                                    <th class="{{ $key_name == 'model_code' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 164.5px;" wire:click="sorting('model_code')">Đời xe
                                    </th>
                                    <th class="{{ $key_name == 'color' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 164.5px;" wire:click="sorting('color')">Màu
                                    </th>
                                    <th class="{{ $key_name == 'color_code' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 164.5px;" wire:click="sorting('color_code')">Mã màu
                                    </th>
                                    <th class="{{ $key_name == 'status' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 150.5px;" wire:click="sorting('status')">Trạng thái</th>
                                    <th class="{{ $key_name == 'type' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 150.5px;" wire:click="sorting('type')">Loại</th>
                                    <th class="{{ $key_name == 'seller_name' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 164.5px;" wire:click="sorting('seller_name')">NV BH</th>
                                    <th class="{{ $key_name == 'assembler_name' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 164.5px;" wire:click="sorting('assembler_name')">NV KT
                                    </th>
                                    <th class="{{ $key_name == 'created_at' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 164.5px;" wire:click="sorting('created_at')">Ngày tạo</th>
                                    <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 220.5px;">Thao tác</th>
                                </tr>
                            </thead>
                            <div wire:loading class="loader"></div>
                            <tbody>
                                @forelse ($data as $row)
                                    <tr data-parent="" data-index="1" role="row" class="odd">
                                        <td>
                                            <input style="margin-left: 30%" type="checkbox"
                                                value="{{ $row->customers_id . '_' . $row->id }}" name="listSelected"
                                                wire:model="listSelected" class="check-box-order" />
                                        </td>
                                        <td class="sorting_1" style="text-align: center;">
                                            {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                        </td>
                                        <td>{{ $row->chassic_no }}</td>
                                        <td>{{ $row->engine_no }}</td>

                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->address . ($row->ward ? ', ' . $row->ward : '') . ($row->district ? ', ' . $row->district : '') . ($row->city ? ', ' . $row->city : '') }}
                                        </td>
                                        <td>{{ numberFormat($row->total_money_original) }}</td>

                                        <td>
                                            {{ $row->contract_number }}
                                        </td>
                                        <td>
                                            @if ($row->contract_number)
                                                {{ numberFormat($row->installment_money) }}
                                            @endif
                                        </td>
                                        <td>{{ numberFormat($row->total_money) }}</td>

                                        <td>{{ $row->model_code }}</td>
                                        <td>{{ $row->color }}</td>
                                        <td>{{ $row->color_code }}</td>
                                        <td>
                                            @if (!$row->isvirtual)
                                                @if ($row->status == 1)
                                                    <span class="badge badge-success"> Đã thanh toán </span>
                                                @elseif ($row->status == 2)
                                                    <span class="badge badge-primary"> Chưa thanh toán </span>
                                                @endif
                                            @else
                                                <span class="badge badge-default"> Đơn ảo </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($row->order_type == 1)
                                                @if ($row->type == 1)
                                                    <span>Bán buôn</span>
                                                @else
                                                    <span>Bán lẻ</span>
                                                @endif
                                            @else
                                                <span>Nhập hàng</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $row->seller_name }}
                                        </td>
                                        <td>
                                            {{ $row->assembler_name }}
                                        </td>
                                        <td>{{ ReFormatDate($row->created_at, 'd-m-Y H:i:s') }}</td>
                                        <td class="text-center">
                                            @if ($row->type == 1)
                                                <a href="{{ route('motorbikes.ban-buon.index', ['id' => $row->id, 'show' => 'true']) }}"
                                                    class="btn btn-warning btn-xs m-r-5" data-toggle="tooltip"
                                                    target="_blank" data-original-title="Xem">
                                                    <i class="fa fa-eye font-14"></i>
                                                </a>
                                            @elseif($row->type == 2)
                                                <a href="{{ route('motorbikes.ban-le.index', ['id' => $row->id, 'show' => 'true']) }}"
                                                    class="btn btn-warning btn-xs m-r-5" data-toggle="tooltip"
                                                    target="_blank" data-original-title="Xem">
                                                    <i class="fa fa-eye font-14"></i>
                                                </a>
                                            @endif
                                            @if ($row->status != 1 && !$row->isvirtual)
                                                @if ($row->type == 1)
                                                    <a href="{{ route('motorbikes.ban-buon.index', ['id' => $row->id]) }}"
                                                        class="btn btn-primary btn-xs m-r-5" data-toggle="tooltip"
                                                        target="_blank" data-original-title="Sửa">
                                                        <i class="fa fa-pencil font-14"></i>
                                                    </a>
                                                @elseif($row->type == 2)
                                                    <a href="{{ route('motorbikes.ban-le.index', ['id' => $row->id]) }}"
                                                        class="btn btn-primary btn-xs m-r-5" data-toggle="tooltip"
                                                        target="_blank" data-original-title="Sửa">
                                                        <i class="fa fa-pencil font-14"></i>
                                                    </a>
                                                @endif
                                                <a href="#" data-toggle="modal" data-target="#deleteModal"
                                                    class="btn btn-danger delete-category btn-xs m-r-5"
                                                    data-toggle="tooltip" data-original-title="Xóa"
                                                    wire:click='deleteId({{ $row->id }})'>
                                                    <i class="fa fa-trash font-14"></i></a>
                                                <a href="{{ route('ketoan.thu.index', ['customerId' => $row->customers_id, 'orderId' => $row->id]) }}"
                                                    target="_blank" class="btn btn-warning btn-xs m-r-5"
                                                    data-toggle="tooltip" data-original-title="Thu tiền">
                                                    <i class="fa fa-money font-14"></i></a>
                                            @endif
                                            @if ($row->type == 2 && !$row->isvirtual && $row->order_details_id)
                                                <span data-toggle="tooltip" title="Xuất Excel">
                                                    <button type="button"
                                                        wire:click="exportOrder({{ $row->order_details_id }})"
                                                        class="btn btn-primary btn-xs m-r-5"
                                                        data-original-title="Xuất Excel"><i class="fa fa-download"
                                                            aria-hidden="true">
                                                        </i></button>
                                                </span>
                                                <span data-toggle="tooltip" title="in phiếu">
                                                    <a href="{{ route('motorbikes.orders.print', ['id' => $row->order_details_id]) }}"
                                                        target="_blank" class="btn btn-primary btn-xs m-r-5"
                                                        data-toggle="tooltip" data-original-title="in phiếu">
                                                        <i class="fa fa-print font-14"></i>
                                                    </a>
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="19" class="text-center text-danger">Không có bản ghi nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if (count($data) > 0)
                                <tr data-parent="" data-index="1" role="row">
                                    <td class="font-weight-bold" colspan="6">Tổng</td>
                                    <td class="font-weight-bold">{{ numberFormat($this->sumTotalMoneyOriginal) }}
                                    </td>
                                    <td></td>
                                    <td class="font-weight-bold">{{ numberFormat($this->sumInstallmentMoney) }}</td>
                                    <td class="font-weight-bold">{{ numberFormat($this->sumTotalMoney) }}</td>
                                    <td colspan="9"></td>
                                </tr>
                            @endif
                        </table>
                    </div>

                </div>
                @if (count($data) > 0)
                    {{ $data->links() }}
                @endif
            </div>

            @include('livewire.common.modal._modalDelete')
            @include('livewire.common.modal._modalExport')
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#searchStatus').on('change', function(e) {
            var data = $('#searchStatus').select2("val");
            @this.set('searchStatus', data);
        });
        $('#Type').on('change', function(e) {
            var data = $('#Type').select2("val");
            @this.set('searchType', data);
        });
    });
</script>
