<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Danh sách mã phụ tùng</div>
            </div>
            <div class="ibox-body">
                <div class="form-group row">
                    <label for="SupplyCode" class="col-1 col-form-label">Mã phụ tùng</label>
                    <div class="col-3">
                        <input id="nameCate" wire:model.debounce.500ms="nameCate" type="text" class="form-control">
                    </div>
                </div>

                <div class="form-group row justify-content-center">
                    @include('layouts.partials.button._reset')
                </div>

                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="category-table_filter" class="dataTables_filter">
                                <a href="{{ route('danhmucmaphutung.themmoi.index') }}" class="btn btn-primary"><i
                                        class="fa fa-plus"></i>
                                    Thêm mới</a>

                                    <button type="button"
                                    class="btn btn-info add-new mr-3" data-toggle="modal"
                                    data-target="#modal-form-import"><i class="fa fa-upload" aria-hidden="true"></i>
                                    IMPORT FILE
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div wire:loading class="loader"></div>
                            <table class="table table-striped table-bordered dataTable no-footer"
                                id="category-table" cellspacing="0" width="100%" role="grid"
                                aria-describedby="category-table_info" style="width: 100%;">
                                <thead>
                                    <tr role="row">
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            aria-sort="ascending" aria-label="ID: activate to sort column descending"
                                            style="width: 70px;">STT
                                        </th>
                                        <th wire:click="sorting('code')"
                                            class="@if ($this->key_name == 'code')
                                    {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 174.5px;">Mã PT</th>

                                        <th wire:click="sorting('name')"
                                            class="@if ($this->key_name == 'name')
                                    {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 174.5px;">Tên</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 120.5px;">Đơn vị</th>

                                        <th wire:click="sorting('parentcode')"
                                            class="@if ($this->key_name == 'parentcode')
                                    {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 174.5px;">Mã PT cha</th>


                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 120.5px;">&nbsp;</th>
                                    </tr>
                                    <thead>
                                    <tbody>
                                        @forelse ($dataAC as $item)
                                            <tr data-parent="" data-index="1" role="row" class="data_table">
                                                <td class="sorting_1">
                                                    {{ ($dataAC->currentPage() - 1) * $dataAC->perPage() + $loop->iteration }}
                                                </td>
                                                <td>{{ $item->code }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->unit }}</td>
                                                <td>{{ $item->parentcode }}</td>


                                                <td class="text-center">
                                                    <a href="{{ route('danhmucmaphutung.capnhat.index', ['id' => $item->id]) }}"
                                                        class="btn btn-primary btn-xs m-r-5" data-toggle="tooltip"
                                                        data-original-title="Sửa"><i
                                                            class="fa fa-pencil font-14"></i></a>
                                                    <a href="#category-table"
                                                        class="btn btn-danger delete-category btn-xs m-r-5"
                                                        style="cursor: pointer" data-target="#modal-form-delete"
                                                        data-toggle="modal" data-original-title="Xóa"
                                                        wire:click="deleteId({{ $item->id }})"><i
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
                    @if (count($dataAC) > 0)
                        {{ $dataAC->links() }}
                    @endif
                    @include('layouts.partials.button._deleteForm')
                    @include('layouts.partials.button._exportForm')
                </div>
            </div>
        </div>
    </div>
    @include('layouts.partials.button._importForm')
    <script>
         window.livewire.on('close-modal-import', () => {
            document.getElementById('modal-form-import').click();
        });
    </script>
</div>
