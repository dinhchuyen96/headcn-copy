<div>
    <div class="page-heading">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fa fa-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Danh sách trả lại hàng mua</li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Quản lý danh sách</div>
            </div>
            <div class="ibox-body">
                <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_length" id="category-table_length"><label>Hiển thị <select
                                        name="category-table_length" aria-controls="category-table" wire:model="perPage"
                                        class="form-control form-control-sm">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select></label></div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div id="category-table_filter" class="dataTables_filter">
                                <a href="{{ route('hangtralai.mua.create') }}" class="btn btn-primary"><i
                                        class="fa fa-plus"></i>
                                    Thêm mới</a>
                            </div>
                        </div>
                    </div>
                    @if (count($data) > 0)
                        {{ $data->links() }}
                    @endif
                    <div class="row">
                        <div class="col-sm-12 table-responsive">
                            <table class="table table-striped table-bordered dataTable no-footer"
                                id="category-table" cellspacing="0" width="100%" role="grid"
                                aria-describedby="category-table_info" style="width: 100%;">
                                <thead>
                                    <tr role="row">
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 30px;">ID</th>
                                        <th>Tên sản phẩm</th>
                                        <th wire:click="sorting('return_quantity')"
                                            class="@if ($this->key_name == 'return_quantity')
                                            {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 100px;">Số lượng</th>
                                        <th wire:click="sorting('created_at')"
                                            class="@if ($this->key_name == 'created_at')
                                            {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 150px;">Ngày tạo</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 80px;"></th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td class="text-center">{{ $item->id }}</td>
                                            <td>
                                                @php
                                                    if ($item->return_product_type) {
                                                        echo $item->accessory->name;
                                                    } else {
                                                        echo $item->motobike->model_code . ' - ' . $item->motobike->chassic_no . ' - ' . $item->motobike->engine_no;
                                                    }
                                                @endphp
                                            </td>
                                            <td>{{ numberFormat($item->return_quantity) }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('hangtralai.mua.edit', $item->id) }}"
                                                    class="btn btn-primary btn-xs m-r-5" title="Sửa"><i
                                                        class="fa fa-pencil font-14"></i></a>
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
</div>
