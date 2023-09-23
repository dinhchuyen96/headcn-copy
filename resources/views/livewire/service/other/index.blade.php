<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Quản lý danh sách dịch vụ khác</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="customer" class="col-1 col-form-label ">Khách hàng</label>
                        <div class="col-3">

                              
                            <div wire:ignore>
                                <select name='customerPhone' id="customerPhone"
                                    data-ajax-url="{{ route('customers.getCustomerByPhoneOrNameWithId.index') }}"
                                    class="custom-select">
                                </select>
                            </div>
                            {{-- <select id="customer" name="customer" wire:model.lazy="customer"
                                class="custom-select select2-box form-control">
                                <option value="">--- Chọn khách hàng ---</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->name . ' - ' . $customer->phone }}
                                    </option>
                                @endforeach
                            </select> --}}
                        </div>

                        <label for="service" class="col-1 col-form-label ">Loại DV</label>
                        <div class="col-3">
                            <select id="service" name="service" wire:model.lazy="service"
                                class="custom-select select2-box form-control">
                                <option value="">--- Chọn DV ---</option>
                                @foreach ($listService as $service)
                                    <option value="{{ $service->id }}">
                                        {{ $service->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <label for="Time" class="col-1 col-form-label ">Thời gian</label>
                        @include('layouts.partials.input._inputDateRangerNow')
                    </div>

                    <div class="form-group row other-sevice-filter">
                        <label for="fixerId" class="col-1 col-form-label ">NV kĩ thuật</label>
                        <div class="col-3">
                            <select id="fixerId" name="fixerId" wire:model.lazy="fixerId"
                                class="custom-select select2-box form-control">
                                <option value="">--- Chọn ---</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <label for="service" class="col-1 col-form-label ">ID</label>
                        <div class="col-3">
                            <input id="search_id" name="search_id" type="text" wire:model.lazy='search_id'
                                class="form-control">
                        </div>
                        <label for="search_status" class="col-1 col-form-label ">Trạng thái</label>
                        <div class="col-3">
                            <select id="search_status" name="search_status" wire:model.lazy="search_status"
                                class="custom-select form-control">
                                <option value=""></option>
                                <option value="1">Đã thanh toán</option>
                                <option value="2">Chưa thanh toán</option>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group pb-3">
                        <label for="service" class="col-1 col-form-label ">Địa chỉ</label>
                        <div class="col-3">
                            <input id="search_address" name="search_address" type="text"
                                wire:model.lazy='search_address' class="form-control">
                        </div>
                        <label for="customer" class="col-1 col-form-label ">Tổng tiền</label>
                        <div class="col-3">
                            <input id="search_total_price" name="search_total_price" type="text"
                                wire:model.lazy='search_total_price' class="form-control">
                        </div>
                    </div>
                </form>

                <div class="dataTables_wrapper dt-bootstrap4 no-footer pt-4">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="category-table_filter" class="dataTables_filter">
                                <button @if (count($data) == 0) disabled @endif name="submit"
                                    data-target="#exportModal" data-toggle="modal" type="button"
                                    class="btn btn-warning add-new"><i class="fa fa-file-excel-o"></i> Export file</button>
                                <a href="{{ route('xemay.dichvukhac.create.index') }}" class="btn btn-primary"><i
                                        class="fa fa-plus"></i> Thêm mới</a>
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
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            aria-sort="ascending" aria-label="ID: activate to sort column descending"
                                            style="width: 164.5px;">ID
                                        </th>
                                        <th wire:click="sorting('customer_id')"
                                            class="@if ($this->key_name == 'customer_id') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Tên khách hàng</th>
                                        <th wire:click="sorting('customer_id')"
                                            class="@if ($this->key_name == 'customer_id') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Địa chỉ</th>
                                        <th wire:click="sorting('total_money')"
                                            class="@if ($this->key_name == 'total_money') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Tổng tiền</th>
                                        <th aria-controls="category-table" style="width: 164.5px;">Loại DV</th>
                                        <th wire:click="sorting('status')"
                                            class="@if ($this->key_name == 'status') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Trạng thái</th>
                                        <th wire:click="sorting('created_at')"
                                            class="@if ($this->key_name == 'created_at') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Ngày hạch toán</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 160.5px;">NV Kỹ thuật</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 160.5px;">Thao tác</th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td>DICHVU_{{ $item->id }}</td>
                                            <td>{{ $item->customer->name ?? '' }}<br>{{ $item->customer->phone ?? '' }}
                                            </td>
                                            <td>
                                                @if ($item->customer)
                                                    {{ $item->customer->address . ($item->customer->districtCustomer ? ', ' . $item->customer->districtCustomer->name : '') . ($item->customer->provinceCustomer ? ', ' . $item->customer->provinceCustomer->name : '') }}
                                                @endif
                                            </td>
                                            <td>
                                                {{ $item->total_money ? number_format($item->total_money) : '0' }}
                                            </td>
                                            <td>
                                                @foreach ($item->otherService as $otherService)
                                                    <p>{{ $otherService->listService->title }}</p>
                                                @endforeach

                                            </td>
                                            <td>
                                                @if ($item->status == 1)
                                                    <span class="badge badge-success" data="<?= $item->status ?>"> Đã
                                                        thanh toán </span>
                                                @else
                                                    <span class="badge badge-primary" data="<?= $item->status ?>"> Chưa
                                                        thanh toán </span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ empty($item->accounting_date) ? date('d-m-Y', strtotime($item->created_at)) : date('d-m-Y', strtotime($item->accounting_date)) }}
                                            </td>
                                            <td>
                                                @php
                                                    if ($item->fixBy) {
                                                        echo $item->fixBy->name;
                                                    }
                                                @endphp
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('xemay.dichvukhac.show.index', ['id' => $item->id]) }}"
                                                    class="btn btn-warning btn-xs m-r-5" data-toggle="tooltip"
                                                    data-original-title="Xem"><i class="fa fa-eye font-14"></i></a>

                                                <a href="{{ route('xemay.dichvukhac.edit.index', ['id' => $item->id]) }}"
                                                    class="btn btn-primary btn-xs m-r-5" data-toggle="tooltip"
                                                    data-original-title="Sửa"><i class="fa fa-pencil font-14"></i></a>

                                                <span data-toggle="tooltip" title="Xoá">
                                                    <button type="button" data-toggle="modal" data-target="#deleteModal"
                                                        wire:click="deleteId({{ $item->id }})"
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

                                                @if ($item->status != 1)
                                                    <a href="{{ route('ketoan.thu.index', ['customerId' => $item->customer_id, 'orderId' => $item->id]) }}"
                                                        target="_blank" class="btn btn-warning btn-xs m-r-5"
                                                        data-toggle="tooltip" data-original-title="Thu tiền">
                                                        <i class="fa fa-money font-14"></i></a>
                                                @endif
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
                placeholder: '--- Chọn khách hàng ---',
            });
        };
    document.addEventListener('DOMContentLoaded', function() {
        
        $('#service').on('change', function(e) {
            var data = $('#service').select2("val");
            @this.set('service', data);
        });
        $('#fixerId').on('change', function(e) {
            var data = $('#fixerId').select2("val");
            @this.set('fixerId', data);
        });
        
        setSelect2Customer();
        
        $('#customerPhone').on('change', function(e) {
            var data = $('#customerPhone').select2("val");
            @this.set('customer', data);
        });
    });
</script>
