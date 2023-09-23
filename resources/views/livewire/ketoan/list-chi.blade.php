<div>

    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thông tin phiếu chi</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="CustomerName" class="col-1 col-form-label ">Loại phiếu chi</label>
                        <div class="col-3">
                            <select name="paymentType" id="paymentType" class="custom-select select2-box"
                                wire:model='paymentType'>
                                <option value="">--Tất cả--</option>
                                <option value="8">Nhập phụ tùng</option>
                                <option value="9">Nhập xe</option>
                                <option value="10">Chi nội bộ</option>
                                <option value="11">Công việc ngoài</option>
                                <option value="12">Trả lại hàng bán</option>
                            </select>
                        </div>

                        @if ($showServiceType)
                            <label for="serviceType" class="col-1 col-form-label ">Loại dịch vụ</label>
                            <div tabindex="0" class='col-3'>
                                <select wire:model="serviceType" name='serviceType' id="serviceType"
                                    class="custom-select">
                                    <option value="">--Tất cả--</option>
                                    @foreach ($listService as $value)
                                        <option value="{{ $value['id'] }}">
                                            {{ $value['title'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <label class="col-1 col-form-label ">Nhân viên chi</label>
                        <label class="col-3 col-form-label">{{ $receptionName }}</label>
                        <label for="nccCode" class="col-1 col-form-label ">MÃ NCC</label>
                        <div class="col-3" id="customerPhoneDiv">
                            <select wire:model="nccCode" name='nccCode' id="nccCode" class="custom-select select2-box">
                                <option hidden value=''>Chọn mã nhà cung cấp</option>
                                @foreach ($codeSupplyList as $item)
                                    <option {{ $nccCode == $item->code ? 'selected' : ''}}
                                     value="{{ $item->code }}">
                                        {{ $item->codeAndName }}</option>
                                @endforeach
                            </select>
                            @error('nccCode')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                    <div wire:loading class="loader"></div>
                    <div class="form-group row" id="supplyCodeDiv">

                        <label for="customerDatePay" class="col-1 col-form-label">Ngày chi <span class="text-danger">*</span></label>
                        <div class="col-3">
                            <input tabindex="3" id="customerDatePay" name="customerDatePay" type="date"
                                class="form-control" wire:model.lazy='customerDatePay'
                                max='{{ now()->format('Y-m-d') }}'>
                            @error('customerDatePay')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="Amount" class="col-1 col-form-label ">Số tiền chi <span class="text-danger">*</span></label>
                        <div class="col-3">
                            <input tabindex="1" id="customerMoney" name="customerMoney" type="text"
                                class="form-control format_number" onkeypress="return onlyNumberKey(event)"
                                placeholder='Nhập số tiền chi' wire:model.defer='customerMoney'>
                            @error('customerMoney')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="customerNote" class="col-1 col-form-label ">Ghi chú <span class="text-danger">*</span></label>
                        <div class="col-3">
                            <textarea tabindex="2" class="form-control" id="customerNote" name="customerNote" rows="1"
                                placeholder="Nhập ghi chú" wire:model.defer='customerNote'></textarea>
                            @error('customerNote')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="accountMoney" class="col-1 col-form-label ">TK thanh toán <span class="text-danger">*</span></label>
                        <div tabindex="1" class="col-3">
                            <select wire:model="accountMoney" name='accountMoney' id="accountMoney"
                                class="custom-select select2-box">
                                <option value=''>Chọn tài khoản</option>
                                @foreach ($accountMoneyList as $key => $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->account_name }}</option>
                                @endforeach
                            </select>
                            @error('accountMoney')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>

                    </div>
                </form>

                <div class="ibox-head p-0" style='display: block;'>
                    <div class="ibox-title pull-left">Danh sách đơn hàng chưa thanh toán</div>
                </div>
                @if ($paymentType!=12)
                <div class="row">
                    <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer mt-2">
                        <div class="row">
                            {{-- <div class="col-sm-12">
                                <div id="category-table_filter" class="dataTables_filter"><label>Tìm kiếm:<input
                                            type="search" class="form-control form-control-sm" placeholder=""
                                            aria-controls="category-table"></label></div>
                            </div> --}}
                        </div>
                        <div class="col-sm-12 table-responsive">
                            <table class="table table-striped table-bordered dataTable no-footer"
                                id="category-table" cellspacing="0" width="100%" role="grid"
                                aria-describedby="category-table_info" style="width: 100%;">
                                <thead>
                                    <tr role="row">
                                        <th style="width: 20px;">
                                            <input type="checkbox" wire:model="checkAll" checked />
                                        </th>
                                        <th style="width: 20px;" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" aria-sort="ascending"
                                            aria-label="ID: activate to sort column descending">
                                            STT
                                        </th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Tên KH / NCC</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Địa chỉ</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Tổng tiền</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Phân loại</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Trạng thái</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Hạn thanh toán</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Ngày tạo</th>
                                        {{-- <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 100.5px;">Thao tác</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $key => $item)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td><input type="checkbox" wire:model="checkOrders"
                                                    value="{{ $item->id }}"></td>
                                            <td class="sorting_1">
                                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $customerName }}</td>
                                            <td>{{ $customerAddress }}</td>
                                            <td>{{ numberFormat($item->total_money) }}</td>
                                            <td>
                                                @if ($item->category == 1 && $item->type == 3)
                                                    Nhập phụ tùng
                                                @endif
                                                @if ($item->category == 2 && $item->type == 3)
                                                    Nhập xe
                                                @endif
                                                @if ($item->category == 6 && $item->type == 3)
                                                    Chi phí khác
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-default"> Chưa thanh toán</span>
                                            </td>
                                            <td class="text-danger">
                                                {{ reFormatDate($item->date_payment, 'd-m-Y H:i:s') }}</td>
                                            <td>{{ reFormatDate($item->created_at, 'd-m-Y H:i:s') }}</td>
                                            {{-- <td class="text-center">
                                                <a href=""
                                                    class="btn btn-danger delete-category btn-xs m-r-5 tag_a_delete"
                                                    data-toggle="modal" data-target="#deleteModal"
                                                    wire:click="deleteId({{ $item->id }})"
                                                    data-original-title="Xóa"><i class="fa fa-trash font-14"></i>
                                                </a>
                                            </td> --}}
                                        </tr>
                                    @empty
                                        <tr class="text-center text-danger">
                                            <!-- <td colspan="9">Không có bản ghi</td> -->
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            @include('livewire.common.modal._modalDelete')
                        </div>

                            @if (count($data) > 0)
                                {{ $data->links() }}
                            @endif
                        @else
                        <!---ds cac return part-->
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                    id="category-table" cellspacing="0" width="100%" role="grid"
                                    aria-describedby="category-table_info" style="width: 100%;">
                                    <thead>
                                        <tr role="row">
                                            <th style="width: 20px;">

                                            </th>
                                            <th style="width: 20px;" tabindex="0" aria-controls="category-table"
                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                aria-label="ID: activate to sort column descending">
                                                STT
                                            </th>
                                            <th>Mã hàng</th>
                                            <th>Tên hàng</th>
                                            <th>Số lượng</th>
                                            <th>Giá nhập</th>
                                            <th>Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data as $key => $item)
                                            <tr data-parent="" data-index="1" role="row" class="odd">
                                                <td><input type="checkbox" wire:model="checkOrders"
                                                        value="{{ $item->id }}"></td>
                                                <td class="sorting_1">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td> {{ $item->code}} </td>
                                                <td> {{ $item->name}} </td>
                                                <td> {{ $item->item_qty}} </td>
                                                <td> {{ $item->item_price }} </td>
                                                <td> {{ $item->amount }} </td>
                                            </tr>
                                        @empty
                                            <tr class="text-center text-danger">
                                                <td colspan="9">Không có bản ghi</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                @include('livewire.common.modal._modalDelete')
                            </div>
                        </div>
                        <!--end ds cac return part --->
                        @endif
                        <div class="form-group row mt-5">
                            <div class="col-12 text-center">
                                <button type="button" class="btn btn-primary" wire:loading.attr="disabled"
                                    wire:click.prevent='createPhieuChi'>Tạo phiếu chi</button>

                            </div>
                        </div>


                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#paymentType').on('change', function(e) {
            var data = $('#paymentType').select2("val");
            @this.set('paymentType', data);

            @this.set('showServiceType', false);
            if (parseInt(data) == 10) {
                @this.set('showServiceType', true);
            }
        });
        $('#accountMoney').on('change', function(e) {
            var data = $('#accountMoney').val();
            @this.set('accountMoney', data);
        });
        $('#nccCode').on('change', function(e) {
            var data = $('#nccCode').select2("val");
            @this.set('nccCode', data);
        });
    });
    document.addEventListener('setPayDatePicker', function() {
        setDatePickerUI();
    });
    document.addEventListener('livewire:load', function() {
        setDatePickerUI();

    });

    function setDatePickerUI() {
        $("#customerDatePay").kendoDatePicker({
            format: "dd/MM/yyyy"
        });
        var customerDatePay = $("#customerDatePay").data("kendoDatePicker");
        customerDatePay.max(new Date());
        customerDatePay.bind("change", function() {
            var value = this.value();
            if (value != null) {
                var datestring = moment(value).format('YYYY-MM-DD');
                @this.set('customerDatePay', datestring);
            }
        });
    };
    document.addEventListener('confirmPrintPdfChi', function(event) {
        let titleMessage = 
            'Thêm mới phiếu chi thành công. Bạn có muốn in phiếu thu bằng pdf không?';
        Swal.fire({
            title: titleMessage,
            icon: 'success',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy bỏ',
            showCancelButton: true,
            showCloseButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                let url = event.detail.url;
                window.open(
                    url,
                    '_blank'
                );
            }
        })
    });
</script>
