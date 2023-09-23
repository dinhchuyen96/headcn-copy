<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Danh sách phụ tùng chờ thay thế</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="accessoryId" class="col-1 col-form-label ">Thông tin PT</label>
                        <div tabindex="1" class="col-3">
                            <select wire:model="accessoryId" name='accessoryId' id="accessoryId"
                                class="custom-select select2-box">
                                <option value=''>Chọn thông tin PT</option>
                                @foreach ($listSelectAccessory as $item)
                                    <option value="{{ $item->id }}">{{ $item->code }} - {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>

                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="category-table_filter" class="dataTables_filter">
                                <button type="button" class="btn btn-warning add-new" data-toggle="modal"
                                    data-target="#exportModal" {{ count($data) ? '' : 'disabled' }}>
                                    <i class="fa fa-file-excel-o"></i> Export file
                                </button>
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
                                        <th aria-controls="category-table">STT
                                        </th>
                                        <th class="{{ $key_name == 'accessories_code' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table"
                                            wire:click='sorting("accessories_code")'>Mã phụ tùng</th>
                                        <th class="{{ $key_name == 'accessories_name' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table"
                                            wire:click='sorting("accessories_name")'>Tên phụ tùng</th>
                                        <th class="{{ $key_name == 'order_details_quantity' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            wire:click='sorting("order_details_quantity")'>Số lượng</th>
                                        <th class="{{ $key_name == 'customer_name' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            wire:click='sorting("customer_name")'>Tên khách hàng</th>
                                        <th class="{{ $key_name == 'mortorbike_number' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            wire:click='sorting("mortorbike_number")'>Biển số xe</th>
                                        <th class="{{ $key_name == 'repair_date' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            wire:click='sorting("repair_date")'>Ngày làm dịch vụ</th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    @forelse ($data as $key => $item)
                                        <tr>
                                            <td>
                                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $item->accessories_code }}</td>
                                            <td>{{ $item->accessories_name }}</td>
                                            <td>{{ $item->order_details_quantity }}</td>
                                            <td>{{ $item->customer_name . ' - ' . $item->customer_phone }}</td>
                                            <td>{{ $item->mortorbike_number }}</td>
                                            <td>{{ $item->repair_date }}</td>

                                        </tr>
                                    @empty
                                        <tr class="text-center text-danger">
                                            <td colspan="10">Không có bản ghi</td>
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
    @include('livewire.common.modal._modalExport')
</div>
@section('js')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            $('#accessoryId').on('change', function(e) {
                var data = $('#accessoryId').select2("val");
                @this.set('accessoryId', data);
            });
        });
    </script>
@endsection
