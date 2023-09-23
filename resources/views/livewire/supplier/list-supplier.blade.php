<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Quản lý danh sách nhà cung cấp</div>
            </div>
            <div class="ibox-body">
                <div class="form-group row">
                    <label for="SupplyCode" class="col-1 col-form-label">Mã nhà cung cấp</label>
                    <div class="col-3">
                        <input id="SupplyCode" wire:model.debounce.500ms="supplyCode" type="text"
                            class="form-control">
                    </div>
                    <label for="SupplyName" class="col-1 col-form-label">Tên nhà cung cấp</label>
                    <div class="col-3">
                        <input id="SupplyName" wire:model.debounce.500ms="supplyName" type="text"
                            class="form-control">
                    </div>
                    <label for="SupplyAdress" class="col-1 col-form-label">Địa chỉ</label>
                    <div class="col-3">
                        <input id="SupplyAdress" wire:model.debounce.500ms="supplyAddress" type="text"
                            class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="SupplyUrl" class="col-1 col-form-label">Trang chủ</label>
                    <div class="col-3">
                        <input id="SupplyUrl" wire:model.debounce.500ms="supplyUrl" type="text" class="form-control">
                    </div>
                    <label for="SupplyPhone" class="col-1 col-form-label">Số điện thoại</label>
                    <div class="col-3">
                        <input id="SupplyPhone" wire:model.debounce.500ms="supplyPhone" type="number"
                            class="form-control">
                    </div>
                    <label for="Email" class="col-1 col-form-label">Email</label>
                    <div class="col-3">
                        <input id="Email" wire:model.debounce.500ms="email" type="text" class="form-control">
                    </div>
                </div>

                <div class="form-group row justify-content-center">
                    @include('layouts.partials.button._reset')
                </div>

                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="category-table_filter" class="dataTables_filter">
                                <a href="{{ route('nhacungcap.themmoi.index') }}" class="btn btn-primary"><i
                                        class="fa fa-plus"></i>
                                    Thêm mới</a>
                                <button data-target="#modal-form-export" data-toggle="modal" type="button"
                                    class="btn btn-warning add-new" {{ count($data) ? '' : 'disabled' }}><i
                                        class="fa fa-file-excel-o"></i> Export file</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 table-responsive">
                            <div wire:loading class="loader"></div>
                            <table class="table table-striped table-bordered dataTable no-footer"
                                id="category-table" cellspacing="0" width="100%" role="grid"
                                aria-describedby="category-table_info" style="width: 100%;">
                                <thead>
                                    <tr role="row">
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            aria-sort="ascending" aria-label="ID: activate to sort column descending"
                                            style="width: 70px;">ID
                                        </th>
                                        <th wire:click="sorting('code')"
                                            class="@if ($this->key_name == 'code')
                                    {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Mã NCC</th>
                                        <th wire:click="sorting('name')"
                                            class="@if ($this->key_name == 'name')
                                    {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Tên NCC</th>
                                        <th wire:click="sorting('address')"
                                            class="@if ($this->key_name == 'address')
                                    {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Địa chỉ</th>
                                        <th wire:click="sorting('url')"
                                            class="@if ($this->key_name == 'url')
                                    {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Trang chủ</th>
                                        <th wire:click="sorting('phone')"
                                            class="@if ($this->key_name == 'phone')
                                    {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">SĐT</th>
                                        <th wire:click="sorting('email')"
                                            class="@if ($this->key_name == 'email')
                                    {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Email</th>
                                        <th wire:click="sorting('created_at')"
                                            class="@if ($this->key_name == 'created_at')
                                    {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Ngày tạo</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 120.5px;">Thao tác</th>
                                    </tr>
                                    <thead>
                                    <tbody>
                                        @forelse ($data as $daum)
                                            <tr data-parent="" data-index="1" role="row" class="data_table">
                                                <td class="sorting_1">
                                                    {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                                </td>
                                                <td>{{ $daum->code }}</td>
                                                <td>{{ $daum->name }}</td>
                                                <td>{{ $daum->address . ($daum->ward_name ? ', ' . $daum->ward_name : '') . ($daum->district_name ? ', ' . $daum->district_name : '') . ($daum->province_name ? ', ' . $daum->province_name : '') }}
                                                </td>
                                                <td><a href="{{ $daum->url }} "
                                                        target="_blank">{{ $daum->url }}</a></td>
                                                <td>
                                                    {{ $daum->phone }}
                                                </td>
                                                <td>{{ $daum->email }}</td>
                                                <td>{{ reFormatDate($daum->created_at, 'd/m/Y') }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('nhacungcap.xemthongtin.index', ['id' => $daum->id]) }}"
                                                        class="btn btn-warning btn-xs m-r-5" data-toggle="tooltip"
                                                        data-original-title="Xem"><i class="fa fa-eye font-14"></i></a>
                                                    <a href="{{ route('nhacungcap.capnhat.index', ['id' => $daum->id]) }}"
                                                        class="btn btn-primary btn-xs m-r-5" data-toggle="tooltip"
                                                        data-original-title="Sửa"><i
                                                            class="fa fa-pencil font-14"></i></a>
                                                    <a href="#category-table"
                                                        class="btn btn-danger delete-category btn-xs m-r-5"
                                                        style="cursor: pointer" data-target="#modal-form-delete"
                                                        data-toggle="modal" data-original-title="Xóa"
                                                        wire:click="deleteId({{ $daum->id }})"><i
                                                            class="fa fa-trash font-14"></i></a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center text-danger">Không có bản ghi </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                            </table>
                        </div>
                    </div>
                    @if (count($data) > 0)
                        {{ $data->links() }}
                    @endif
                    @include('layouts.partials.button._deleteForm')
                    @include('layouts.partials.button._exportForm')
                </div>
            </div>
        </div>
    </div>
</div>
