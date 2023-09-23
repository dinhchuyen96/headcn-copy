<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Quản lý danh sách chi nội bộ</div>
            </div>
            <div class="ibox-body">
                <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="category-table_filter" class="dataTables_filter">
                                <a href="{{ route('chinoibo.create') }}" class="btn btn-primary"><i
                                        class="fa fa-plus"></i>Thêm mới</a>
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
                                            style="width: 30px;">ID</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1">Nội dung
                                        </th>
                                        <th wire:click="sorting('created_at')"
                                            class="@if ($this->key_name == 'created_at')
                                            {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 165px;">Ngày tạo</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 100px;">Thao tác</th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td class="text-center">{{ $item->id }}</td>
                                            <td>{{ $item->content }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('chinoibo.edit', $item->id) }}"
                                                    class="btn btn-primary btn-xs m-r-5" title="Sửa"><i
                                                        class="fa fa-pencil font-14"></i></a>
                                                <a href="" wire:click="deleteId({{ $item->id }})"
                                                    data-toggle="modal" data-target="#ModalDelete"
                                                    class="btn btn-danger delete-category btn-xs m-r-5" title="Xóa"><i
                                                        class="fa fa-trash font-14"></i></a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="text-center text-danger">
                                            <td colspan="12">Không có bản ghi</td>
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
        <div class="modal-backdrop fade in" style="height: 100%;"></div>
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
</div>