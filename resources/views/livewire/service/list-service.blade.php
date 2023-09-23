<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Quản lý danh sách đơn hàng </div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="CustomerName" class="col-1 col-form-label ">Số khung,số máy</label>
                        <div class="col-3">
                            <input type="text" name="searchTerm" class="form-control size13"
                                wire:model.debounce.1000ms="searchTerm" id='name' autocomplete="off">
                        </div>
                        <label for="PayStatus" class="col-1 col-form-label ">Trạng thái thanh toán</label>
                        <div class="col-3">
                            <select name="" id="searchStatus" class="form-control size13 select2-box"
                                wire:model.lazy="searchStatus">
                                <option value="0">--Chọn--</option>
                                <option value="2">Chưa thanh toán</option>
                                <option value="1">Đã thanh toán</option>
                            </select>
                        </div>
                        <label for="Time" class="col-1 col-form-label ">Thời gian</label>
                        @include('layouts.partials.input._inputDateRangerNow')
                    </div>
                    <div class="form-group row">
                            <label for="number_moto" class="col-1 col-form-label">Biển số xe</label>
                            <div class="col-3">
                                <input type="text" name="searchMotorNumber" class="form-control size13"
                                    wire:model.debounce.1000ms="searchMotorNumber" id='number_moto' autocomplete="off">
                            </div>
                            <label for="searchDigest" class="col-1 col-form-label ">Phân loại</label>
                        <div class="col-3">
                            <select name="" id="searchDigest" class="form-control select2-box"
                                wire:model.lazy="searchDigest" style="height: 36px">
                                <option value="0">--Chọn--</option>
                                <option value="3">Bảo dưỡng định kì</option>
                                <option value="4">Sửa chữa thông thường</option>
                            </select>
                        </div>
                        <label for="searchTimes" class="col-1 col-form-label ">Lần KTĐK</label>
                        <div class="col-3">
                            <select name="" id="searchTimes" class="form-control select2-box"
                                wire:model.lazy="searchTimes" style="height: 36px">
                                <option value="">--Chọn--</option>
                                <option value="1">Lần 1</option>
                                <option value="2">Lần 2</option>
                                <option value="3">Lần 3</option>
                                <option value="4">Lần 4</option>
                                <option value="5">Lần 5</option>
                                <option value="6">Lần 6</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="CustomerName" class="col-1 col-form-label ">Họ tên KH</label>
                        <div tabindex="1" class="col-3" id="customerPhoneDiv">
                            <div wire:ignore>
                                <select name='customerPhone' id="customerPhone"
                                    data-ajax-url="{{ route('customers.getCustomerByPhoneOrName.index') }}"
                                    class="custom-select">
                                </select>
                            </div>
                        </div>
                        <label for="phone_number" class="col-1 col-form-label ">Số điện thoại</label>
                        <div class="col-3">
                            <input id="phone_number" name="phone_number" type="text" wire:model.lazy='phone_number'
                                class="form-control">
                        </div>
                        <label for="work_status" class="col-1 col-form-label ">Trạng Thái Sửa Chữa</label>
                        <div class="col-3">
                            <select name="work_status" id="work_status" class="form-control size13 select2-box"
                                wire:model.lazy="work_status">
                                <option value="">--Chọn--</option>
                                <option value="1">Chưa làm gì</option>
                                <option value="2">Đã đc tiếp nhận</option>
                                <option value="3">Đã thực hiện</option>
                            </select>
                        </div>
                    </div>
                </form>
                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer pt-4">
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div id="category-table_filter" class="dataTables_filter">
                                <button @if (count($data) == 0) disabled @endif name="submit"
                                    data-target="#exportModal" data-toggle="modal" type="button"
                                    class="btn btn-warning add-new"><i class="fa fa-file-excel-o"></i> Export file</button>
                                <a class="btn btn-warning {{ $customerSelectedId && $orderSelectedId && count($listSelected) > 0 ? '' : 'disabled ' }}"
                                   target="{{ $customerSelectedId && $orderSelectedId && count($listSelected) > 0 ? '_blank' : '' }}"
                                   href="{{ $customerSelectedId && $orderSelectedId && count($listSelected) > 0? route('ketoan.thu.index', ['customerId' => $customerSelectedId, 'orderId' => $orderSelectedId]): 'javascript:void(0)' }}"><i
                                        class="fa fa-money"></i> Thu
                                    tiền</a>
                            </div>
                        </div>
                    </div>
                    <div class="row" style='overflow: auto'>
                        <div wire:loading class="loader"></div>
                        <div class="col-sm-12 table-responsive ">
                            <table class="table table-striped table-bordered dataTable no-footer horizontal-scrollable"
                                   id="category-table" cellspacing="0" width="100%" role="grid"
                                aria-describedby="category-table_info"
                                style="width: 100%;overflow:auto;white-space: nowrap;">
                                <thead>
                                    <tr role="row">
                                        <th></th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            aria-sort="ascending" aria-label="ID: activate to sort column descending"
                                            style="width: 164.5px;">ID
                                        </th>
                                        <th wire:click="sorting('customer_id')"
                                            class="@if ($this->key_name == 'customer_id') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Tên khách hàng</th>
                                        <th wire:click="sorting('phone')"
                                            class="@if ($this->key_name == 'phone') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">SĐT</th>
                                        <th wire:click="sorting('customer_id')"
                                            class="@if ($this->key_name == 'customer_id') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Địa chỉ</th>
                                        <th wire:click="sorting('total_money')"
                                            class="@if ($this->key_name == 'total_money') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 200.5px;">Tổng tiền</th>
                                        <th>Số khung</th>
                                        <th>Số máy</th>
                                        <th>Biển số</th>
                                        <th wire:click="sorting('status')"
                                            class="@if ($this->key_name == 'status') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Trạng thái</th>

                                        <th wire:click="sorting('category')"
                                            class="@if ($this->key_name == 'category') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Phân loại</th>

                                        <th aria-controls="category-table" style="width: 164.5px;">Lần KTĐK</th>
                                        <th aria-controls="category-table">Tiền công</th>
                                        <th style="width: 160.5px;">Ghi chú</th>

                                        <th wire:click="sorting('created_at')"
                                            class="@if ($this->key_name == 'created_at') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Ngày tạo</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 160.5px;">Thao tác</th>
                                    </tr>
                                </thead>

                                <tbody id="body_content">
                                    @forelse ($data as $item)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td>
                                                @if ($item->status != 1)
                                                    <input style="margin-left: 30%" type="checkbox"
                                                        value="{{ $item->customer_id . '_' . $item->id }}"
                                                        name="listSelected" wire:model="listSelected"
                                                        class="check-box-order" />
                                                @endif
                                            </td>
                                            <td>DICHVU_{{ $item->id }}</td>
                                            <td>{{ $item->customer->name ?? '' }}</td>
                                            <td>{{ $item->customer->phone ?? '' }}</td>
                                            <td>
                                                @if ($item->customer)
                                                    {{ $item->customer->address . (isset($item->customer->wardCustomer) ? ', ' . $item->customer->wardCustomer->name : '') . (isset($item->customer->districtCustomer) ? ', ' . $item->customer->districtCustomer->name : '') . (isset($item->customer->provinceCustomer) ? ', ' . $item->customer->provinceCustomer->name : '') }}
                                                @endif
                                            </td>
                                            <td>{{ $item->total_money ? number_format($item->total_money) : '0' }}
                                            </td>
                                            <td>{{ $item->motorbike->chassic_no }}</td>
                                            <td>{{ $item->motorbike->engine_no }}</td>
                                            <td>{{ $item->motorbike->motor_numbers }}</td>
                                            <td>
                                                @if ($item->status == 1)
                                                    <span class="badge badge-success"> Đã thanh toán</span>
                                                @else
                                                    <span class="badge badge-primary"> Chưa thanh toán </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->category == 3)
                                                    Bảo dưỡng định kì
                                                @else
                                                    Sửa chữa thông thường
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->category == 3)
                                                    {{ $item->periodic->periodic_level }}
                                                @endif
                                            </td>
                                            <td>
                                                {{ number_format($item->repairTask->sum(function ($option) {
                                                    return $option->price - ($option->price * $option->promotion / 100);
                                                }))}}
                                            </td>
                                            <td>
                                                @if ($item->category == 3)
                                                    {{ $item->periodic->result_repair }}
                                                @else
                                                    {{ $item->repairBill->result_repair }}
                                                @endif
                                            </td>
                                            <td>{{ empty($item->periodic) ? date('d-m-Y H:i:s', strtotime($item->created_at)) : date('d-m-Y H:i:s', strtotime($item->periodic->check_date)) }}</td>
                                            <td class="text-center">
                                                @if ($item->category == 3)
                                                    <a href="{{ route('dichvu.bao-duong-dinh-ki.index', ['id' => $item->id, 'show' => 'true']) }}"
                                                        target="_blank" class="btn btn-warning btn-xs m-r-5"
                                                        data-toggle="tooltip" data-original-title="Xem"><i
                                                            class="fa fa-eye font-14"></i></a>
                                                @endif
                                                @if ($item->category == 4)
                                                    <a href="{{ route('dichvu.sua-chua-thong-thuong.xem.index', ['id' => $item->id]) }}"
                                                        target="_blank" class="btn btn-warning btn-xs m-r-5"
                                                        data-toggle="tooltip" data-original-title="Xem"><i
                                                            class="fa fa-eye font-14"></i></a>
                                                @endif

                                                @if (($item->category == 4 || $item->category == 3)  && $item->status != 1)
                                                    @if ($item->category == 3)
                                                        <a href="{{ route('dichvu.bao-duong-dinh-ki.index', ['id' => $item->id, 'edit' => 'true']) }}"
                                                            target="_blank" class="btn btn-primary btn-xs m-r-5"
                                                            data-toggle="tooltip" data-original-title="Sửa"><i
                                                                class="fa fa-pencil font-14"></i></a>
                                                    @endif
                                                    @if ($item->category == 4)
                                                        <a href="{{ route('dichvu.sua-chua-thong-thuong.sua.index', ['id' => $item->id]) }}"
                                                            target="_blank" class="btn btn-primary btn-xs m-r-5"
                                                            data-toggle="tooltip" data-original-title="Sửa"><i
                                                                class="fa fa-pencil font-14"></i></a>
                                                    @endif
                                                    <span data-toggle="tooltip" title="Xoá">
                                                        <button type="button" data-toggle="modal"
                                                            data-target="#deleteModal"
                                                            wire:click="deleteId({{ $item->id }})"
                                                            class="btn btn-danger delete-category btn-xs m-r-5"
                                                            data-original-title="Xóa"><i
                                                                class="fa fa-trash font-14"></i></button>
                                                    </span>
                                                @endif
                                                @if ($item->status != 1)
                                                    <a href="{{ route('ketoan.thu.index', ['customerId' => $item->customer_id, 'orderId' => $item->id]) }}"
                                                        target="_blank" class="btn btn-warning btn-xs m-r-5"
                                                        data-toggle="tooltip" data-original-title="Thu tiền">
                                                        <i class="fa fa-money font-14"></i></a>
                                                @endif
                                                <span data-toggle="tooltip" title="in phiếu">
                                                    <a href="{{ route('dichvu.dsdonhang.print', ['id' => $item->id]) }}"
                                                        target="_blank" class="btn btn-primary btn-xs m-r-5"
                                                        data-toggle="tooltip" data-original-title="in phiếu">
                                                        <i class="fa fa-print font-14"></i>
                                                    </a>
                                                </span>
                                                <div wire:ignore.self class="modal fade" id="deleteModal"
                                                    tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-backdrop fade in" style="height: 100%;"></div>
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h2 class="modal-title" id="exampleModalLabel">Xác
                                                                    nhận xóa</h2>
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
                                    @empty
                                        <tr>
                                            <td colspan="16" class="text-center text-danger">Không có bản ghi nào.</td>
                                        </tr>
                                    @endforelse

                                    @if (count($data) > 0)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td colspan="2">Tổng tiền</td>
                                            <td colspan="3"></td>
                                            <td colspan="1">{{ number_format($sumMoney) }}</td>
                                            <td colspan="10"></td>
                                        </tr>
                                    @endif
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#searchStatus').on('change', function(e) {
            var data = $('#searchStatus').select2("val");
            @this.set('searchStatus', data);
        });
        $('#work_status').on('change', function(e) {
            var data = $('#work_status').select2("val");
            @this.set('work_status', data);
        });
        $('#searchDigest').on('change', function(e) {
            var data = $('#searchDigest').select2("val");
            @this.set('searchDigest', data);
        });
        $('#searchTimes').on('change', function(e) {

            var data = $('#searchTimes').select2("val");
            @this.set('searchTimes', data);

        });
        setSelect2Customer();

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
                    const itemFirst = {
                        id: "0",
                        text: "--Chọn--"
                    };
                    data.unshift(itemFirst);
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
        $("#category-table tbody").on('change','.statusfix',function(e){
            let id=$(this).attr('data-id')
            $.ajax({
                        url: route('servicelist.edit.workstatus', {id: id}),
                        method: 'POST',
                        data:{
                            status:e.target.value
                        },
                        success: function (response) {
                        },error:function(error){

                        }
                    })
            });

    };




</script>
