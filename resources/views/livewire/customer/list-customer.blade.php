<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">Quản lý danh sách khách hàng</div>
        </div>
        <div class="ibox-body">
            <div>
                <div class="form-group row">
                    <label for="CustomerName" class="col-1 col-form-label">Họ và tên</label>
                    <div class="col-3">
                        <input id="CustomerName" name="CustomerName" type="text" class="form-control" value=""
                            wire:model.debounce.1000ms="searchName">
                    </div>
                    <label for="CustomerAdress" class="col-1 col-form-label">Địa chỉ</label>
                    <div class="col-3">
                        <input id="CustomerAdress" name="CustomerAdress" type="text" class="form-control" value=""
                            wire:model.debounce.1000ms="searchAddress">
                    </div>
                    <label for="CustomerPhone" class="col-1 col-form-label">Số điện thoại</label>
                    <div class="col-3">
                        <input id="CustomerPhone" name="CustomerPhone" type="number" class="form-control" value=""
                            wire:model.debounce.1000ms="searchPhone">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="Birthday" class="col-1 col-form-label">Ngày sinh</label>
                    <div class="col-3">
                        <input type="date" id="transactionDate" class="form-control input-date-kendo"
                            max='{{ date('Y-m-d') }}' wire:model.debounce.1000ms="searchBirthday">
                    </div>
                    <label for="Sex" class="col-1 col-form-label">Giới tính</label>
                    <div class="col-3">
                        <select id="Sex" name="Sex" type="text" class="form-control" value=""
                            wire:model.debounce.1000ms="searchSex">
                            <option value="" selected="">Tất cả</option>
                            <option value="1">Nam</option>
                            <option value="2">Nữ</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row justify-content-center">
                    <div class="col-1">
                        @include('layouts.partials.button._reset')
                    </div>
                </div>
            </div>
            <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="category-table_filter" class="dataTables_filter">
                            <a href="{{ route('customers.create.index') }}" class="btn btn-primary"><i
                                    class="fa fa-plus"></i>
                                Thêm mới</a>
                                <button data-target="#ModalExport" data-toggle="modal" type="button" class="btn btn-warning add-new"
                                {{ count($data) ? '' : 'disabled' }}><i class="fa fa-file-excel-o"></i> Export
                                file</button>
                            <button type="button" class="btn btn-info add-new" data-toggle="modal"
                                data-target="#modal-form-import-cnbh"><i class="fa fa-upload" aria-hidden="true"></i>
                                IMPORT CNBH
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 table-responsive">
                        <table class="table table-striped table-sm table-bordered dataTable no-footer"
                            id="category-table" cellspacing="0" width="100%" role="grid"
                            aria-describedby="category-table_info" style="width: 100%;">
                            <thead>
                                <tr role="row">
                                    <th class="{{ $key_name == 'code' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        wire:click="sorting('code')" style="width: 7%;">ID
                                    </th>
                                    <th class="{{ $key_name == 'name' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 10%;" wire:click="sorting('name')">Họ tên</th>
                                    <th class="{{ $key_name == 'address' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 10%;" wire:click="sorting('address')">Địa chỉ</th>
                                    <th class="{{ $key_name == 'phone' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 10%;" wire:click="sorting('phone')">SĐT</th>
                                    <th class="{{ $key_name == 'birthday' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 7%;" wire:click="sorting('birthday')">Ngày sinh</th>
                                    <th class="{{ $key_name == 'sex' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 5%;" wire:click="sorting('sex')">Giới tính</th>
                                    <th class="{{ $key_name == 'job' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 7%;" wire:click="sorting('job')">Nghề nghiệp</th>
                                    <th class="{{ $key_name == 'point' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 7%;" wire:click="sorting('point')">Tích điểm</th>
                                    <th class="{{ $key_name == 'created_at' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 8%;" wire:click="sorting('created_at')">Ngày tạo</th>
                                    <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 7%;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $item)
                                    <tr data-parent="" data-index="1" role="row" class="odd">
                                        <td>{{ $item->code }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->address . (isset($item->wardCustomer) ? ', ' . $item->wardCustomer->name : '') . (isset($item->districtCustomer) ? ', ' . $item->districtCustomer->name : '') . (isset($item->provinceCustomer) ? ', ' . $item->provinceCustomer->name : '') }}
                                        </td>
                                        <td>{{ $item->phone }}</td>
                                        <td>{{ formatBirthday($item->birthday) }}</td>
                                        <td>{{ getSexName($item->sex) }}</td>
                                        <td>{{ $item->job }}</td>
                                        <td>{{ numberFormat($item->point) }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('customers.show.index', $item->id) }}"
                                                class="btn btn-warning btn-xs m-r-5" data-toggle="tooltip"
                                                data-original-title="Xem"><i class="fa fa-eye font-14"></i></a>
                                            <a href="{{ route('customers.edit.index', $item->id) }}"
                                                class="btn btn-primary btn-xs m-r-5" data-toggle="tooltip"
                                                data-original-title="Sửa"><i class="fa fa-pencil font-14"></i></a>
                                            <a href="{{ route('customers.gift-change.index', $item->id) }}"
                                                class="btn btn-primary btn-xs m-r-5" data-toggle="tooltip"
                                                data-original-title="Quà tặng"><i class="fa fa-gift font-14"></i></a>
                                            <a href="#" data-toggle="modal" data-target="#deleteModal"
                                                class="btn btn-danger delete-category btn-xs m-r-5"
                                                data-toggle="tooltip" data-original-title="Xóa"
                                                wire:click='deleteId({{ $item->id }})'><i
                                                    class="fa fa-trash font-14"></i></a>

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
    <div wire:ignore.self class="modal fade" id="ModalExport" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Xác nhận</h5>
            </div>
            <div class="modal-body">
                <p>Bạn có muốn xuất file không?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Đóng</button>
                <button type="button" wire:click.prevent="export()" class="btn btn-primary close-modal"
                    data-dismiss="modal">Đồng ý</button>
            </div>
        </div>
    </div>
</div>
    @include('livewire.common.modal._modalDelete')
    @include('layouts.partials.button._importCNBHForm')
</div>
