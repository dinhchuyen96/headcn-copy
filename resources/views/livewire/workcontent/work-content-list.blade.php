<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Quản lý danh mục nội dung công việc</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="name" class="col-1 col-form-label ">Nội dung</label>
                        <div class="col-3">
                            <input id="name" placeholder="Nội dung" type="text" class="form-control"
                                wire:model="name">
                        </div>
                        <label for="type" class="col-1  col-form-label ">Loại công việc</label>
                        <div class="col-3">
                            <select
                                wire:model="type"
                                id="type" class="custom-select select2-box">
                                <option value="0">Công việc trong</option>
                                <option value="1">Công việc ngoài</option>
                            </select>
                        </div>
                    </div>
                </form>
                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="category-table_filter" class="dataTables_filter">
                                <a href="{{ route('work-content.create.index') }}" class="btn btn-primary"><i
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
                                        <th wire:click="sorting('name')"
                                            class="@if ($this->key_name == 'name') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 90%">Nội dung công việc</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 90%">Loại công việc</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 90%">Action</th>

                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td>
                                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $item->name }}</td>
                                            @if ($item->type == 0)
                                                <td><span class="badge badge-success">Công việc trong</span></td>
                                            @else
                                                <td>
                                                    <span class="badge badge-primary">Công việc ngoài</span>
                                                </td>
                                            @endif
                                            <td>
                                                <a href="{{ route('work-content.edit.index', ['id' => $item->id]) }}"
                                                    class="btn btn-primary btn-xs m-r-5" data-toggle="tooltip"
                                                    data-original-title="Sửa"><i class="fa fa-pencil font-14"></i></a>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#type').on('change', function(e) {
            var data = $('#type').select2("val");
            @this.set('type', data);
        });
    });
</script>
