<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thông tin khách hàng</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="searchInfoCustomer" class="col-1 col-form-label">Thông tin khách hàng</label>
                        <div wire:ignore class="col-3">
                            <select id="searchInfoCustomer" name="searchInfoCustomer"
                                data-ajax-url="{{ route('customers.getCustomerByPhoneOrName.index') }}"
                                class="custom-select form-control">
                            </select>
                        </div>
                        <label for="" class="col-1 col-form-label ">Phân loại<span
                                class="text-danger"> *</span></label>
                        <div class="col-3">
                            <select name="category-table_length" aria-controls="category-table"
                                wire:model='selectOption' class="custom-select select2-box form-control"
                                id="selectOption">
                                {{-- <option value="">--chọn--</option> --}}
                                <option value="1">Tin nhắn 4S</option>
                                {{-- <option value="2">Bảo dưỡng</option> --}}
                            </select>
                            @error('selectOption')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="smsOption" class="col-1 col-form-label">Nội dung tin nhắn</label>
                        <div class="col-3">
                            <div style="border: 1px solid rgb(194, 189, 189);height:70px;padding:3px">
                                <span id="smsOption" style="margin: 5px">{!! $content !!}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-1 col-form-label">Ngày gửi<span class="text-danger"> *</span></label>
                        <div class="col-3">
                            <input type="date" class="form-control" wire:model="param1" name="param1" id="param1" />
                            @error('param1')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>

                        <label class="col-1 col-form-label">Tên Head<span class="text-danger"> *</span></label>
                        <div class="col-3">
                            <input type="text" class="form-control" wire:model.debounce.1000ms="param2" name="param2"
                                id="param2" />
                            @error('param2')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        @if ($selectOption == 1)
                        <label class="col-1 col-form-label">ĐT <span class="text-danger"> *</span></label>
                            <div class="col-3">
                                <input type="text" class="form-control" wire:model.debounce.1000ms="param3"
                                    name="param3" id="param3" onkeypress="return onlyNumberKey(event)" />
                                @error('param3')
                                    @include('layouts.partials.text._error')
                                @enderror
                            </div>
                        @endif

                    </div>
                    <div class="row form-group">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-2">
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-primary" data-target="#sendSMS"
                                        data-toggle="modal">Gửi tin
                                        nhắn <i class="fa fa-commenting" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>


                    </div>
                </form>
                {{-- <div class="form-group row justify-content-center">
                <div class="col-1">
                    @include('layouts.partials.button._reset')
                </div>
            </div> --}}
            <div wire:loading class="loader"></div>
            <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer table-responsive">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-striped table-bordered dataTable no-footer"
                            id="category-table" cellspacing="0" width="100%" role="grid"
                            aria-describedby="category-table_info" style="width: 100%;">
                            <thead>
                                <tr role="row">
                                    <th style="width: 10px;">
                                        <input style="margin-left: 30%" type="checkbox" name="listSends"
                                            wire:model="isCheckAll" class="check-box-order" />
                                    </th>
                                    <th style="width: 50px;">STT
                                    </th>
                                    <th class="{{ $key_name == 'name' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 150.5px;" wire:click="sorting('name')">Họ và tên</th>
                                    <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 100.5px;">Số điện thoại</th>
                                    <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 100.5px;">Địa chỉ</th>

                                    <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 164.5px;">Trạng thái
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($data as $item)
                                    {{-- <h3>{{dd($data)}}</h3> --}}
                                    <tr data-parent="" data-index="1" role="row" class="odd">
                                        <td>
                                            <input style="margin-left: 30%" type="checkbox"
                                                value="{{ (string) $item->id }}" name="listSends"
                                                wire:model.defer="listSend" class="check-box-order" />
                                        </td>
                                        <td>
                                            {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                        </td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->phone }}</td>
                                        <td>{{ $item->address .(isset($item->wardCustomer) ? ', ' . $item->wardCustomer->name : '') .(isset($item->districtCustomer) ? ', ' . $item->districtCustomer->name : '') .(isset($item->provinceCustomer) ? ', ' . $item->provinceCustomer->name : '') }}
                                        </td>
                                        <td>

                                            @if ($item->is_sent_4s == 0)
                                                -Tin nhắn 4S:<span class='badge badge-warning'>Chưa gửi</span>
                                                <br />
                                            @else
                                                -Tin nhắn 4S:<span class='badge badge-success'>Đã gửi</span>
                                                ({{ $item->last_datetime_sent_4s }})
                                                <br />
                                            @endif
                                            @if ($item->motorbikes_count > 0)
                                                @if ($item->is_sent_ktdk == 0)
                                                    -Tin nhắn KTĐK: <span class='badge badge-warning'>Chưa gửi</span>
                                                    <br />
                                                @else
                                                    -Tin nhắn KTĐK:<span class='badge badge-success'>Đã gửi</span>
                                                    ({{ $item->last_datetime_sent_ktdk }})
                                                    <br />
                                                @endif
                                            @endif
                                            @if ($item->is_sent_thank_you == 0)
                                                -Tin nhắn cảm ơn KH: <span class='badge badge-warning'>Chưa gửi</span>
                                                <br />
                                            @else
                                                -Tin nhắn cảm ơn KH:<span class='badge badge-success'>Đã gửi</span>
                                                ({{ $item->last_datetime_sent_thank_you }})
                                                <br />
                                            @endif
                                            @if ($item->is_sent_birtday == 0)
                                                -Tin nhắn chúc sinh nhật: <span class='badge badge-warning'>Chưa gửi</span>
                                                <br />
                                            @else
                                                -Tin nhắn chúc sinh nhật:<span class='badge badge-success'>Đã gửi</span>
                                                ({{ $item->last_datetime_sent_birtday }})
                                            @endif


                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center text-danger">Không có bản ghi nào.</td>
                                        <br />
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
    <div class="modal fade" id="sendSMS" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="exampleModalLabel">Send sms cho khách hàng</h2>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xuất gửi sms?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-basic" data-dismiss="modal">Quay lại</button>
                    <button type="button" wire:click='SentMgs' class="btn btn-primary" data-dismiss="modal"
                        id='btn-upload-film'>Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        setSelect2Customer();
        $('#selectOption').on('change', function(e) {
            var data = $('#selectOption').select2("val");
            @this.set('selectOption', data);
        });
    });
    document.addEventListener('setDatePicker', function() {
        setDatePickerUI();
    });
    document.addEventListener('select2Customer', function() {
        setSelect2Customer();
    });
    document.addEventListener('livewire:load', function() {
        setDatePickerUI();
    });

    function setSelect2Customer() {
        let ajaxUrl = $('#searchInfoCustomer').data("ajaxUrl");
        $('#searchInfoCustomer').select2({
            ajax: {
                url: ajaxUrl,
                data: function(params) {
                    var query = {
                        search: params.term,
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            },
            placeholder: 'Nhập tên hoặc SĐT để tìm kiếm',
        });
        $('#searchInfoCustomer').on('change', function(e) {
            var data = $('#searchInfoCustomer').select2("val");
            @this.set('searchInfoCustomer', data);
        });
    };

    function setDatePickerUI() {
        $("#param1").kendoDatePicker({
            format: "dd/MM/yyyy"
        });
        var param1 = $("#param1").data("kendoDatePicker");
        param1.bind("change", function() {
            var value = this.value();
            if (value != null) {
                var datestring = moment(value).format('YYYY-MM-DD');
                @this.set('param1', datestring);
            }
        });
    };
</script>
