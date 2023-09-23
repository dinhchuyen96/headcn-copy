<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Quản lý danh sách MTOC</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="searchById" class="col-1 col-form-label ">Mã MTOC</label>
                        <div class="col-3">
                            <input type="text" name="searchById" class="form-control size13"
                                wire:model.debounce.1000ms="searchById" id='MTOCCode' autocomplete="off">
                        </div>
                        <label for="searchByOptionCode" class="col-1 col-form-label ">Danh mục</label>
                        <div class="col-3">
                            <input type="text" name="searchByOptionCode" class="form-control size13"
                                wire:model.debounce.1000ms="searchByOptionCode" id='searchByOptionCode'
                                autocomplete="off">
                        </div>
                        <label for="searchByColorCode" class="col-1 col-form-label ">Mã màu xe</label>
                        <div class="col-3">
                            <input type="text" name="searchByColorCode" class="form-control size13"
                                wire:model.debounce.1000ms="searchByColorCode" id='searchByColorCode'
                                autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="searchByModelType" class="col-1 col-form-label ">Phân loại</label>
                        <div class="col-3">
                            <input type="text" name="searchByModelType" class="form-control size13"
                                wire:model.debounce.1000ms="searchByModelType" id='searchByModelType'
                                autocomplete="off">
                        </div>
                        <label for="searchBySuggestPrice" class="col-1 col-form-label ">Màu xe</label>
                        <div class="col-3">
                            <input id="searchNameColor" name="searchNameColor"
                                wire:model.debounce.1000ms="searchNameColor" type="text" class="form-control"
                                value="">
                        </div>
                        <label for="searchByModelCode" class="col-1 col-form-label ">Đời xe</label>
                        <div class="col-3">
                            <input id="searchByModelCode" name="searchByModelCode"
                                wire:model.debounce.1000ms="searchByModelCode" type="text" class="form-control"
                                value="">
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
                                <a href="{{ route('mtoc.create.index') }}" class="btn btn-primary"><i
                                        class="fa fa-plus"></i>
                                    Thêm mới</a>
                                {{-- <button @if (count($data) == 0) disabled @endif name="submit" data-target="#exportModal"
                                    data-toggle="modal" type="button" class="btn btn-warning add-new"><i
                                        class="fa fa-file-excel-o"></i> Export file</button> --}}
                                {{-- <a href="{{ route('mtoc.import.index') }}" class="btn btn-primary"><i
                                        class="fa fa-file-excel-o"></i>
                                    Import MTOC</a> --}}
                                {{-- <button type="button" class="btn btn-info add-new" data-toggle="modal"
                                    data-target="#modal-form-import"><i class="fa fa-file-excel-o"
                                        aria-hidden="true"></i> IMPORT
                                </button> --}}
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
                                        <th wire:click="sorting('mtocd')"
                                            class="@if ($this->key_name == 'mtocd')
                                            {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Mã MTOC</th>
                                        <th wire:click="sorting('option_code')"
                                            class="@if ($this->key_name == 'option_code')
                                            {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Danh mục</th>
                                        <th wire:click="sorting('model_name_s')"
                                            class="@if ($this->key_name == 'model_name_s')
                                            {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Đời xe</th>
                                        <th wire:click="sorting('type_code')"
                                            class="@if ($this->key_name == 'type_code')
                                            {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Phân loại </th>
                                        <th wire:click="sorting('color_code')"
                                            class="@if ($this->key_name == 'color_code')
                                            {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Mã màu </th>
                                        <th wire:click="sorting('color_name')"
                                            class="@if ($this->key_name == 'color_name')
                                            {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Màu xe</th>
                                        <th wire:click="sorting('suggest_price')"
                                            class="@if ($this->key_name == 'suggest_price')
                                            {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Giá đề suất</th>
                                        <th wire:click="sorting('created_at')"
                                            class="@if ($this->key_name == 'created_at')
                                            {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Ngày tạo</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 165.5px;">Thao tác</th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    @forelse ($data as $row)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td class="text-center">{{ $row->id }}</td>
                                            <td>{{ $row->getMTOC() }}</td>
                                            <td>{{ $row->option_code ?? '' }}</td>
                                            <td>{{ $row->model_code ?? '' }}</td>
                                            <td>{{ $row->type_code ?? '' }}</td>
                                            <td>{{ $row->color_code }}</td>
                                            <td>{{ $row->color_name }}</td>
                                            <td>{{ numberFormat($row->suggest_price) ?? '' }}</td>
                                            <td>{{ $row->created_at }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('mtoc.show.index', $row->id) }}"
                                                    class="btn btn-warning btn-xs m-r-5" data-toggle="tooltip"
                                                    data-original-title="Xem"><i class="fa fa-eye font-14"></i></a>
                                                <a href="{{ route('mtoc.edit.index', $row->id) }}"
                                                    class="btn btn-primary btn-xs m-r-5" title="Sửa"><i
                                                        class="fa fa-pencil font-14"></i></a>
                                                <a href="" wire:click="deleteId({{ $row->id }})"
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
        <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
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
@include('layouts.partials.button._importForm')
</div>

@section('js')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            $('#searchModelName').on('change', function(e) {
                var data = $('#searchModelName').select2("val");
                @this.set('searchModelName', data);
            });
            $('#OptionModel').on('change', function(e) {
                var data = $('#OptionModel').select2("val");
                @this.set('searchOptionModel', data);
            });
            $('#Type').on('change', function(e) {
                var data = $('#Type').select2("val");
                @this.set('searchType', data);
            });

        })
    </script>
@endsection
