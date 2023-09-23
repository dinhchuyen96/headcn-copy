<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Quản lý danh sách vai trò</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="CustomerName" class="col-1 col-form-label">Tên vai trò</label>
                        <div class="col-3">
                            <input id="CustomerName" name="UserName" type="text" class="form-control"
                                wire:model.debounce.1000ms="searchName">
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
                                <a href="{{ route('roles.create.index') }}" class="btn btn-primary"><i
                                        class="fa fa-plus"></i>
                                    Thêm mới</a>
                                <button name="submit" type="submit" class="btn btn-warning add-new"
                                    {{ count($data) ? '' : 'disabled' }}><i class="fa fa-file-excel-o"></i> Export
                                    file</button>
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
                                            aria-sort="ascending" aria-label="ID: activate to sort column descending"
                                            style="width: 70px;">STT
                                        </th>
                                        <th class="{{ $key_name == 'name' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click="sorting('name')">Tên vai trò</th>
                                        <th class="{{ $key_name == 'created_at' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click="sorting('created_at')">Ngày tạo</th>
                                        <th class="{{ $key_name == 'created_at' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click="sorting('percentage')">Phần trăm hoa hồng</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 120.5px;">Thao tác</th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    @forelse($data as $key => $value)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td class="sorting_1">
                                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{!! boldTextSearchV2($value->name, $searchName) !!}</td>
                                            <td>{{ date('d-m-Y H:i:s ', strtotime($value->created_at)) }}</td>
                                            <td>{{boldTextSearchV2($value->percentage, $searchName) }}</td>
                                            <td>
                                                <a href="{{ Route('roles.edit.index', $value->id) }}"
                                                    class="btn btn-primary btn-xs m-r-5" data-toggle="tooltip"
                                                    data-original-title="Sửa"><i class="fa fa-pencil font-14"></i></a>
{{--                                                <a href=""--}}
{{--                                                    class="btn btn-danger delete-category btn-xs m-r-5 tag_a_delete"--}}
{{--                                                    data-toggle="modal" data-target="#ModalDelete"--}}
{{--                                                    wire:click="deleteId({{ $value->id }})"--}}
{{--                                                    data-original-title="Xóa"><i class="fa fa-trash font-14"></i>--}}
{{--                                                </a>--}}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="text-center text-danger">
                                            <td colspan="6">Không có bản ghi.</td>
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
    {{-- Modal Delete --}}
    <div wire:ignore.self class="modal fade" id="ModalDelete" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Xác nhận xóa</h5>
                </div>
                <div class="modal-body">
                    <p>Bạn có xóa không? Thao tác này không thể phục hồi!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Đóng</button>
                    <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal"
                        data-dismiss="modal">Xóa</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal edit role --}}
    <div class="modal fade" id="edit_role" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Thay đổi vai trò</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <!--           <span aria-hidden="true">&times;</span> -->
                    </button>
                </div>
                <form action="{{ route('nguoiDung.update_role') }}" method="GET">
                    <div class="modal-body">
                        <div id='div-submit'>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
