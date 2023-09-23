@php
use App\Enum\ReasonChangeInput;
use App\Enum\ReasonType;
@endphp
<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Lịch sử nhập xuất ngoại lệ</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="row">
                        <div class="col-12 col-md-offset-3">
                            <div class="form-group row">
                                <label for="log_type" class="col-1 col-form-label ">Loại ngoại lệ</label>
                                <div class="col-3">
                                    <select wire:model="log_type" id="log_type" class="custom-select select2-box">
                                        <option hidden value="">Tất cả</option>
                                        @foreach ($reasonType as $key => $item)
                                            <option value="{{ $item['value'] }}">{{ $item['text'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                @include('layouts.partials.button._reset')
                            </div>
                        </div>
                    </div>
                </form>

                <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="table-responsive">
                        <div class="table-wrapper">
                            <table class="table table-striped table-bordered dataTable no-footer"
                                id="category-table" cellspacing="0" width="100%" role="grid"
                                aria-describedby="category-table_info" style="width: 100%;">
                                <thead>
                                    <tr role="row">
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            aria-sort="ascending" aria-label="ID: activate to sort column descending"
                                            style="width: 70px;">STT
                                        </th>
                                        <th class="" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" style="width: 164.5px;">Vị trí</th>
                                        <th class="{{ $key_name == 'accessory_code' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("accessory_code")'>Mã phụ
                                            tùng</th>
                                        <th class="{{ $key_name == 'accessory_name' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("accessory_name")'>Tên phụ tùng
                                        </th>
                                        <th class="{{ $key_name == 'accessory_quantity' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("accessory_quantity")'>Số lượng
                                        </th>
                                        <th class="" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" style="width: 164.5px;">Lý do</th>
                                        <th class="" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" style="width: 164.5px;">Ngày tạo</th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    @forelse($historyList as $key => $value)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td class="sorting_1">
                                                {{ ($historyList->currentPage() - 1) * $historyList->perPage() + $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $value->warehouse_name . '-' . $value->position_in_warehouse_name }}
                                            </td>
                                            <td>
                                                {{ $value->accessory_code }}
                                            </td>
                                            <td>
                                                {{ $value->accessory_name }}
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $value->type == 1 ? 'success' : 'warning' }}">{{ $value->type == 1 ? '+' : '-' }} {{ $value->accessory_quantity }}</span>
                                            </td>
                                            <td>
                                                {{ ReasonChangeInput::getDescription($value->reason) }}
                                            </td>
                                            <td>
                                                {{ $value->created_at }}
                                            </td>
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
                    @if (count($historyList) > 0)
                        {{ $historyList->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@section('js')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            $('#log_type').on('change', function(e) {
                var data = $('#log_type').select2("val");
                @this.set('log_type', data);
            });
        })
    </script>
@endsection
