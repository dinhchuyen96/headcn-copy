<div>
    <div class="page-heading">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fa fa-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Danh sách kho quà tặng</li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Quản lý danh sách kho quà tặng</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="StorageName" class="col-1 col-form-label ">Tên kho</label>
                        <div class="col-5">
                            <input id="StorageName" name="StorageName" type="text" class="form-control"
                                wire:model.debounce.1000ms="searchStorageName">
                        </div>
                        <label for="StorageAddress" class="col-1 col-form-label ">Địa chỉ </label>
                        <div class="col-5">
                            <input id="StorageAddress" name="StorageAddress" type="text" class="form-control"
                                wire:model.debounce.1000ms="searchStorageAddress">
                        </div>
                    </div>
                    <div class="form-group row"  wire:ignore>
                        <label for="StorageEstablished" class="col-1 col-form-label ">Ngày lập</label>
                        <div class="col-5">
                            <input id="StorageEstablished" name="StorageEstablished" type="date" class="form-control">
                        </div>
                        <label for="StorageCreated" class="col-1 col-form-label ">Ngày tạo</label>
                        <div class="col-5">
                            <input id="StorageCreated" name="StorageCreated" type="date" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row justify-content-center">
                        <div class="col-1">
                            @include('layouts.partials.button._reset')
                        </div>
                    </div>
                </form>

                <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            @include('layouts.partials.input._perPage')
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div id="category-table_filter" class="dataTables_filter">
                                <a href="{{ route('kho.themmoi.index') }}" class="btn btn-primary"><i
                                        class="fa fa-plus"></i>
                                    Thêm mới</a>
                                <button data-target="#ModalExport" data-toggle="modal" type="button"
                                    class="btn btn-warning add-new" {{ count($data) ? '' : 'disabled' }}><i
                                        class="fa fa-file-excel-o"></i> Export file</button>
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
                                            aria-sort="ascending" aria-label="ID: activate to sort column descending"
                                            style="width: 70px;">ID
                                        </th>
                                        <th class="{{ $key_name == 'name' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("name")'>Tên kho</th>
                                        <th class="{{ $key_name == 'district_name' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("district_name")'>Địa chỉ kho
                                        </th>
                                        <th class="{{ $key_name == 'established_date' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("established_date")'>Ngày
                                            thành lập kho</th>
                                        <th class="{{ $key_name == 'created_at' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("created_at")'>Ngày tạo</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 120.5px;" class='text-center'>Thao tác</th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    @forelse($data as $key => $value)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td class="sorting_1">
                                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{!! boldTextSearchV2($value->name, $searchStorageName) !!}</td>
                                            <td>
                                                {{ $value->address . ($value->district_name ? ', ' . $value->district_name : '') . ($value->province_name ? ', ' . $value->province_name : '') }}
                                            </td>
                                            <td>{{ date('d-m-Y', strtotime($value->established_date)) }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($value->created_at)) }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('kho.capnhat.index',  ['id' => $value->id, 'type' => 'gift']) }}"
                                                    class="btn btn-primary btn-xs m-r-5" data-toggle="tooltip"
                                                    data-original-title="Sửa"><i class="fa fa-pencil font-14"></i></a>
                                                <a href=""
                                                    class="btn btn-danger delete-category btn-xs m-r-5 tag_a_delete"
                                                    data-toggle="modal" data-target="#ModalDelete"
                                                    wire:click="deleteId({{ $value->id }})"
                                                    data-original-title="Xóa"><i class="fa fa-trash font-14"></i>
                                                </a>
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
    <div wire:ignore.self class="modal fade" id="ModalExport" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Xác nhận</h5>
                </div>
                <div class="modal-body">
                    <p>Bạn có muốn xuất file không?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Đóng</button>
                    <button type="button" wire:click.prevent="export()" class="btn btn-primary close-modal"
                        data-dismiss="modal">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
</div>
@section('js')
    <script type="text/javascript">
        StorageEstablished.max =moment(new Date()).format('YYYY-MM-DD');
        document.addEventListener('DOMContentLoaded', function() {
            $('#warehouseProvince').on('change', function(e) {
                var data = $('#warehouseProvince').select2("val");
                @this.set('warehouseProvince', data);
            });
            $('#warehouseDistrict').on('change', function(e) {
                var data = $('#warehouseDistrict').select2("val");
                @this.set('warehouseDistrict', data);
            });

            setDatePickerUI();
        })

        document.addEventListener('setStorageEstablishedPicker', function() {
            setDatePickerUI();
        });

        function setDatePickerUI() {
            $("#StorageEstablished").kendoDatePicker({
                max: new Date(),
                value: new Date(),
                format: 'dd/MM/yyyy',
                change: function() {
                    if (this.value() != null) {
                        window.livewire.emit('setStorageEstablished', {
                            ['StorageEstablished']: this.value() ? this.value().toLocaleDateString(
                                'en-US') : null
                        });
                    }
                }
            });
            $("#StorageCreated").kendoDatePicker({
                max: new Date(),
                value: new Date(),
                format: 'dd/MM/yyyy',
                change: function() {
                    if (this.value() != null) {
                        window.livewire.emit('setStorageCreated', {
                            ['StorageCreated']: this.value() ? this.value().toLocaleDateString(
                                'en-US') : null
                        });
                    }
                }
            });
        };
    </script>
@endsection
