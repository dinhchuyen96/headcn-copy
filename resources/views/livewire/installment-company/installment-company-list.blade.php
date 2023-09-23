<div>
    <div class="page-heading">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fa fa-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Công ty trả góp</li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Quản lý Công ty trả góp</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row pt-3">
                        <label for="name" class="col-1 col-form-label ">Tên công ty</label>
                        <div class="col-5">
                            <input id="name" placeholder="Nội dung" type="text" class="form-control"
                                   wire:model="company_name">
                        </div>
                        <label for="address" class="col-1 col-form-label ">Địa chỉ công ty</label>
                        <div class="col-5">
                            <input id="address" placeholder="Nội dung" type="text" class="form-control"
                                   wire:model="company_address">
                        </div>
                        <label for="rose" class="col-1 col-form-label ">Hoa hồng (%)</label>
                        <div class="col-5">
                            <input id="rose" placeholder="Nội dung" type="text" class="form-control"
                                   wire:model="benefit_percentage">
                        </div>
                    </div>
                </form>
                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
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
                                <a href="{{ route('installment-company.create.index') }}" class="btn btn-primary"><i
                                        class="fa fa-plus"></i>
                                    Thêm mới</a>
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
                                    <th wire:click="sorting('company_name')"
                                        class="@if ($this->key_name == 'company_name') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        >Tên công ty
                                    </th>
                                    <th wire:click="sorting('company_address')"
                                        class="@if ($this->key_name == 'company_address') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        >Địa chỉ
                                    </th>
                                    <th wire:click="sorting('benefit_percentage')"
                                        class="@if ($this->key_name == 'benefit_percentage') {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        >Phần trăm hoa hồng
                                    </th>
                                    <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 120.5px;">Action</th>
                                </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                @forelse ($data as $item)
                                    <tr data-parent="" data-index="1" role="row" class="odd">
                                        <td>
                                            {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                        </td>
                                        <td>{{ $item->company_name }}</td>
                                        <td>{{ $item->company_address }}</td>
                                        <td>{{ $item->benefit_percentage }} %</td>
                                        <td class="text-center">
                                            <a href="{{ route('installment-company.edit.index', ['id' => $item->id]) }}"
                                               class="btn btn-primary btn-xs m-r-5" data-toggle="tooltip"
                                               data-original-title="Sửa"><i
                                                    class="fa fa-pencil font-14"></i></a>
                                            <a href="#category-table"
                                               class="btn btn-danger delete-category btn-xs m-r-5"
                                               style="cursor: pointer" data-target="#modal-form-delete"
                                               data-toggle="modal" data-original-title="Xóa"
                                               wire:click.prevent="delete({{ $item->id }})"><i
                                                    class="fa fa-trash font-14"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="text-center text-danger">
                                        <td colspan="5">Không có bản ghi</td>
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

