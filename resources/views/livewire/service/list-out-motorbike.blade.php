<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Quản lý danh sách xe ngoài</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="SerialNumber" class="col-1 col-form-label">Số khung</label>
                        <div class="col-3">
                            <input id="SerialNumber" wire:model.debounce.1000ms='searchChassic' name="SerialNumber"
                                type="text" class="form-control" value="">
                        </div>
                        <label for="EngineNumber" class="col-1 col-form-label ">Số máy</label>
                        <div class="col-3">
                            <input id="EngineNumber" wire:model='searchEngine' name="EngineNumber" type="text"
                                class="form-control" value="">
                        </div>
                        <label for="searchByName" class="col-1 col-form-label ">Tên khách hàng</label>
                        <div class="col-3">
                            <input id="model" wire:model.debounce.1000ms='searchByName' type="text"
                                class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="searchByNumber" class="col-1 col-form-label ">Số điện thoại</label>
                        <div class="col-3">
                            <input wire:model='searchByNumber' type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row justify-content-center">
                        <div class="col-1">
                            @include('layouts.partials.button._reset')
                        </div>
                    </div>

                </form>
                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12 table-responsive">
                            <table class="table table-striped table-bordered dataTable no-footer"
                                id="category-table" cellspacing="0" width="100%" role="grid"
                                aria-describedby="category-table_info" style="width: 100%;">
                                <thead>
                                    <tr role="row">
                                        <th style="width: 50.5px;">STT</th>
                                        <th class="{{ $key_name == 'chassic_no' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 150.5px;" wire:click="sorting('chassic_no')">Số khung</th>
                                        <th class="{{ $key_name == 'engine_no' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 100.5px;" wire:click="sorting('engine_no')">Số máy</th>

                                        <th class="{{ $key_name == 'model_type' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click="sorting('model_type')">Phân loại đời xe
                                        </th>
                                        <th class="{{ $key_name == 'model_list' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click="sorting('model_list')">Danh mục đời xe
                                        </th>
                                        <th class="{{ $key_name == 'name' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 150.5px;" wire:click="sorting('name')">Họ Tên</th>
                                        <th class="{{ $key_name == 'phone' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 150.5px;" wire:click="sorting('phone')">Số điện thoại
                                        </th>
                                        <th class="{{ $key_name == 'created_at' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 150.5px;" wire:click="sorting('created_at')">Ngày tạo</th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    @forelse ($data as $val)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td style="text-align: center;">
                                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $val->chassic_no }}</td>
                                            <td>{{ $val->engine_no }}</td>
                                            <td>{{ $val->model_list }}</td>
                                            <td>{{ $val->model_type }}</td>
                                            <td>{{ $val->name }}</td>
                                            <td>{{ $val->phone }}</td>
                                            <td>{{ $val->created_at ?? '' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center text-danger">Không có bản ghi nào.</td>
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
        @include('livewire.common.modal._modalDelete')
    </div>
</div>
