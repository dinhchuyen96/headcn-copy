<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Quản lý danh sách người dùng</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="CustomerName" class="col-1 col-form-label">Họ và tên</label>
                        <div class="col-3">
                            <input id="CustomerName" name="UserName" type="text" class="form-control"
                                wire:model.debounce.1000ms="searchUserName">
                        </div>
                        <label for="Email" class="col-1 col-form-label">Email</label>
                        <div class="col-3">
                            <input id="Email" name="Email" type="text" class="form-control"
                                wire:model.debounce.1000ms="searchEmail">
                        </div>
                        <label for="NameAccount" class="col-1 col-form-label">Username</label>
                        <div class="col-3">
                            <input id="NameAccount" name="NameAccount" type="text" class="form-control"
                                wire:model.debounce.1000ms="NameAccount">
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
                                <a href="{{ route('nguoiDung.create.index') }}" class="btn btn-primary"><i
                                        class="fa fa-plus"></i>
                                    Thêm mới</a>
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
                                        <th class="{{ $key_name == 'username' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("username")'>Username</th>
                                        <th class="{{ $key_name == 'name' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("name")'>Họ và tên</th>
                                        <th class="{{ $key_name == 'email' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("email")'>Email</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Quyền</th>
                                        <th class="{{ $key_name == 'created_at' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("created_at")'>Ngày tạo</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 120.5px;" class='text-center'>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $key => $value)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td class="sorting_1">
                                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{!! boldTextSearchV2($value->username, $NameAccount) !!}</td>
                                            <td>{!! boldTextSearchV2($value->name, $searchUserName) !!}</td>
                                            <td>{!! boldTextSearchV2($value->email, $searchEmail) !!}</td>
                                            <td>
                                                @if (!empty($value->roles))
                                                    @foreach ($value->roles as $role)
                                                        <label class="badge badge-success">{{ $role->name }}</label>
                                                    @endforeach
                                                    {{-- <button type="submit" class="fa fa-plus btn-show" data-toggle="modal" data-target="#edit_role" data-id='{{$value->id}}'></button>
                                                <input type="hidden" value='{{ $value->id }}' name="id">
                                                @foreach ($value->roleDontBelongto as $role)
                                                <input type="hidden" value="{{ $role->name }}" note="{{ $role->note }} ">
                                                @endforeach
                                                @foreach ($value->roles as $role)
                                                <input type="hidden" value="{{ $role->name }}" note="{{ $role->note }} ">
                                                @endforeach
                                                <input type="hidden" value='{{ $value->roles()->count() }}'>
                                                <input type="hidden" value='{{ $value->roleDontBelongto->count() }}'> --}}
                                                @endif
                                            </td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($value->created_at)) }}</td>
                                            <td class="text-center">

                                                <a href="{{ route('nguoiDung.edit.index', $value->id) }}"
                                                    class="btn btn-primary btn-xs m-r-5" data-toggle="tooltip"
                                                    data-original-title="Sửa"><i class="fa fa-pencil font-14"></i></a>
                                                @if($value->id != auth()->id())
                                                <a href=""
                                                    class="btn btn-danger delete-category btn-xs m-r-5 tag_a_delete"
                                                    data-toggle="modal" data-target="#ModalDelete"
                                                    wire:click="deleteId({{ $value->id }})"
                                                    data-original-title="Xóa"><i class="fa fa-trash font-14"></i>
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="text-center text-danger">
                                            <td colspan="6">Không có bản ghi</td>
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
