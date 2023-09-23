<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="Time" class="col-1 col-form-label ">Thời gian</label>
                        @include('layouts.partials.input._inputDateRangerNow')
                        <label for="Time" class="col-1 col-form-label text-right">Tình trạng liên hệ</label>
                        <div class="col-3">
                                <select id="contactstatus" class="form-control select2-box"
                                    wire:model.lazy="contactstatus" >
                                    <option hidden value="">--Chọn--</option>
                                    <option value=0>Chưa liên hệ</option>
                                    <option value=1>Đã liên hệ</option>
                                </select>
                        </div>
                        <label for="Time" class="col-1 col-form-label ">Khách hàng</label>
                        <div class="col-3">
                            <input type='text' id='customernameorphone'
                            wire:model.lazy = 'customernameorphone'
                             class='form-control' placeholder='Tên - số điện thoại khách hàng '>
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
                                <button @if (count($data) == 0) disabled @endif name="submit" data-target="#exportModal"
                                    data-toggle="modal" type="button" class="btn btn-warning add-new"><i
                                        class="fa fa-file-excel-o"></i> Export file</button>
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
                                            aria-sort="ascending" aria-label="ID: activate to sort column descending"
                                            >ID
                                        </th>
                                        <th wire:click="sorting('customer_id')"
                                            class="@if ($this->key_name == 'customer_id')
                                    {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                           >Tên khách hàng</th>
                                        <th wire:click="sorting('phone')"
                                            class="@if ($this->key_name == 'phone')
                                    {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                           >SĐT</th>
                                        <th wire:click="sorting('customer_id')"
                                            class="@if ($this->key_name == 'customer_id')
                                    {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            >Địa chỉ</th>
                                        <th wire:click="sorting('total_revenue')"
                                            class="@if ($this->key_name == 'total_revenue')
                                    {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                           >Doanh thu</th>
                                        <th wire:click="sorting('sell_date')"
                                            class="@if ($this->key_name == 'sell_date')
                                    {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                           >Ngày PS <br>Doanh thu</th>

                                        <th wire:click="sorting('created_at')"
                                        class="@if ($this->key_name == 'created_at')
                                {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        >Lần KTDK/Lí do</th>


                                        <th wire:click="sorting('status')"
                                            class="@if ($this->key_name == 'status')
                                    {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            >Tình trạng liên hệ</th>

                                        <th wire:click="sorting('category')"
                                            class="@if ($this->key_name == 'category')
                                    {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            >Hình thức LH</th>

                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->phone }}</td>
                                            <td>
                                                {{ isset($item->address) ? $item->address : ''}}
                                            </td>
                                            <td>{{ isset($item->total_revenue) ? number_format($item->total_revenue) : '0' }}
                                            </td>
                                            <td>{{ isset($item->sell_date) ?
                                                $item->sell_date : ''
                                            }}
                                            </td>
                                            <td>@if (isset($item->periodic_level) && ($item->periodic_level>0))
                                                {{
                                                  'KTDK Lần '. $item->periodic_level }}
                                                @elseif (isset($item->periodic_level) && ($item->periodic_level ==-1))
                                                 {{'Mua xe'}}
                                                @elseif (isset($item->periodic_level) && ($item->periodic_level ==0))
                                                 {{ 'Sửa chữa /PT'}}
                                                @endif

                                            </td>

                                            <td>
                                              {{isset($item->contact_method_id) ? 'Đã LH' : '' }}
                                            </td>
                                            <td class="text-center">
                                                {{isset($item->method_name) ? $item->method_name : '' }}
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
                    @if (count($data) > 0)
                        {{ $data->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Download file</h5>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#contactstatus').on('change', function(e) {
            var data = $('#contactstatus').select2("val");
            @this.set('contactstatus', data);
        });
    });
</script>
