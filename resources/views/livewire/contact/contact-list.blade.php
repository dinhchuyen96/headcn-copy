<div>
    <div class="page-heading">
        <ol class="breadcrumb">
            <li class="fa fa-phone font-20" >
                <a href="{{ route('dashboard') }}"></a>
            </li>
            <li class="fa fa-phone" aria-hidden="true">Liên Hệ</li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Hình Thức Liên Hệ</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="name" class="col-1 col-form-label ">Nội dung</label>
                        <div class="col-5">
                            <input id="method_name" placeholder="Nội dung" type="text" class="form-control"
                                   wire:model="method_name">
                        </div>
                    </div>
                </form>
                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_length" id="category-table_length"><label>Hiển thị <select
                                        name="category-table_length" aria-controls="category-table" wire:model="perPage"
                                        class="form-control form-control-sm">
                                        <option value="1">1</option>
                                        <option value="5">5</option>
                                        <option value="7">7</option>
                                        <option value="10">10</option>
                                    </select></label></div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div id="category-table_filter" class="dataTables_filter">
                                <a href="{{ route('contact.create.index') }}" class="btn btn-primary"><i
                                        class="fa fa-plus"></i>
                                     Thêm Thông Tin Liên Hệ</a>
                            </div>
                        </div>
                    </div>
                    @if (count($data) > 0)
                        {{ $data->links() }}
                    @endif
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                   id="category-table" cellspacing="0" width="100%" role="grid"
                                   aria-describedby="category-table_info" style="width: 100%;">
                                <thead>
                                <tr role="row">
                                    <th tabindex="0" aria-controls="category-table" style="width: 30px;">STT</th>
                                    <th wire:click="sorting('method_name')"
                                        class="@if ($this->key_name == 'method_name') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 90%"> Nội dung </th>
                                    <th tabindex="0" aria-controls="category-table" style="width: 30px;">Action</th>

                                </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                @forelse ($data as $item)
                                    <tr data-parent="" data-index="1" role="row" class="odd">
                                        <td>
                                            {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                        </td>
                                        <td>{{ $item->method_name }}</td>
                                        <td class="text-center">

                                            <a href="{{ route('contact.edit.index', ['id' => $item->id]) }}"
                                               class="btn btn-primary btn-xs m-r-5" data-toggle="tooltip"
                                               data-original-title="Sửa"><i class="fa fa-pencil font-14"></i></a>

                                            <a href=""
                                               class="btn btn-danger delete-category btn-xs m-r-5 tag_a_delete"
                                               data-toggle="modal" data-target="#ModalDelete"
                                               wire:click.prevent="delete({{ $item->id }})"
                                               data-original-title="Xóa"><i class="fa fa-trash font-14"></i>
                                            </a>

                                        </td>
                                    </tr>
                                @empty
                                    <tr class="text-center text-danger">
                                        <td colspan="2">Không có bản ghi</td>
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
</div>
