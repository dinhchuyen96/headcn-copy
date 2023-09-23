<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Quản lý danh mục dịch vụ khác</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="serviceName" class="col-1 col-form-label ">Tên dịch vụ</label>
                        <div class="col-5">
                            <input id="serviceName" placeholder="Tên dịch vụ" type="text" class="form-control"
                                wire:model="serviceName">

                        </div>
                        <label for="serviceType" class="col-1 col-form-label ">Loại DV</label>
                        <div class="col-5">
                            <select wire:model="serviceType" name='serviceType' id="serviceType"
                                class="custom-select select2-box">
                                <option value=''>Chọn loại DV</option>
                                <option value="1">DV khác (Thu)</option>
                                <option value="2">DV khác (Chi)</option>
                            </select>

                        </div>
                    </div>
                    
                </form>
                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="category-table_filter" class="dataTables_filter">
                                <a href="{{ route('servicelist.create.index') }}" class="btn btn-primary"><i
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
                                        <th tabindex="0" aria-controls="category-table" style="width: 30px;">STT</th>
                                        <th wire:click="sorting('title')"
                                            class="@if ($this->key_name == 'title') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 50%">Tên dịch vụ</th>
                                        <th wire:click="sorting('type')"
                                            class="@if ($this->key_name == 'type') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 30%">Loại dịch vụ</th>
                                        <th class="text-center" style="width: 100.5px;">Thao tác</th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td>
                                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $item->title }}</td>
                                            <td>
                                                @if ($item->type == 1)
                                                    <span class="badge badge-primary">DV khác (Thu)</span>
                                                @endif
                                                @if ($item->type == 2)
                                                    <span class="badge badge-warning"> DV khác (Chi)</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('servicelist.edit.index', $item->id) }}"
                                                    class="btn btn-primary btn-xs m-r-5" title="Sửa"><i
                                                        class="fa fa-pencil font-14"></i></a>
                                                <a href="#" wire:click="deleteId({{ $item->id }})"
                                                    data-toggle="modal" data-target="#ModalDelete"
                                                    class="btn btn-danger delete-category btn-xs m-r-5" title="Xóa"><i
                                                        class="fa fa-trash font-14"></i></a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="text-center text-danger">
                                            <td colspan="4">Không có bản ghi</td>
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

@section('js')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            $('#serviceType').on('change', function(e) {
                var data = $('#serviceType').select2("val");
                @this.set('serviceType', data);
            });
        });
    </script>
@endsection
