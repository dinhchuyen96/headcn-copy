<div>

    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thông tin phiếu thu</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">

                    </div>
                    <div wire:loading class="loader"></div>
                    <div class="form-group row">
                        <label for="receiptType" class="col-1 col-form-label ">Loại phiếu thu</label>
                        <div tabindex="0" class='col-3'>
                            <select wire:model="receiptType" name='receiptType' id="receiptType"
                                class="custom-select select2-box">
                                <option value="">--Tất cả--</option>
                                <option value="1">Bán lẻ xe máy</option>
                                <option value="2">Bán buôn xe máy</option>
                                <option value="3">Bán lẻ phụ tùng</option>
                                <option value="4">Bán buôn phụ tùng</option>
                                <option value="5">Dịch vụ BD định kỳ</option>
                                <option value="6">Dịch vụ sửa chữa</option>
                                <option value="7">Nợ cũ</option>
                                <option value="8">Dịch vụ khác</option>
                                <option value="9">Trả góp</option>
                            </select>
                        </div>

                        <label for="serviceType"
                            class="col-1 col-form-label {{ $showServiceType ? '' : 'd-none' }}">Loại dịch vụ</label>
                        <div tabindex="0" class='col-3 {{ $showServiceType ? '' : 'd-none' }}'>
                            <select wire:model="serviceType" name='serviceType' id="serviceType"
                                class="custom-select select2-box">
                                <option value="">--Tất cả--</option>
                                @foreach ($listService as $item)
                                    <option value="{{ $item['id'] }}">
                                        {{ $item['title'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <label for="CustomerName" class="col-1 col-form-label ">Họ tên KH</label>
                        <div tabindex="1" class="col-3" id="customerPhoneDiv">
                            {{-- Old method use jquery -> replace with livewire --}}
                            <div wire:ignore>
                                <select name='customerPhone' id="customerPhone"
                                    data-ajax-url="{{ route('customers.getCustomerByPhoneOrName.index') }}"
                                    data-customer-id="{{ $customerID }}" data-customer-name="{{ $customerName }}"
                                    data-customer-phone="{{ $customerPhone }}" class="custom-select">
                                </select>
                            </div>
    
                        </div>
                        <label for="customerCode" class="col-1 col-form-label ">Mã khách hàng</label>
                        <div class="col-3">
                            <input tabindex="2" id="customerCode" name="customerCode" type="text" class="form-control form-red"
                                placeholder='Nhập mã khách hàng'
                                wire:model.debounce.500ms="customerCode" }}>
                            @error('customerCode')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                    <div wire:loading class="loader"></div>
                    <div class="form-group row">
                        <label class="col-1 col-form-label ">Số tiền phải thu</label>
                        <label class="col-3 col-form-label ">{{ numberFormat($needPaid) }} đ</label>
                        <label class="col-1 col-form-label ">Tổng giá trị đơn hàng</label>
                        <label class="col-3 col-form-label ">{{ numberFormat($orderNotPaid) }} đ</label>
                        <label for="actualPaid" class="col-1 col-form-label ">Số tiền thực thu <span class="text-danger">*</span></label>
                        <div class="col-3">
                            <input tabindex="4" id="actualPaid" name="actualPaid" type="text" class="form-control"
                                onkeypress="return onlyNumberKey(event)" placeholder='Nhập số tiền thu'
                                wire:model.debounce.500ms='actualPaid'>
                            @error('actualPaidConvert')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">

                        <label for="promotionMoney" class="col-1 col-form-label ">Khuyến mãi</label>
                        <div class="col-3">
                            <input tabindex="3" id="promotionMoney" name="promotionMoney" type="text"
                                class="form-control" onkeypress="return onlyNumberKey(event)"
                                placeholder='Khuyến mãi' wire:model.debounce.500ms='promotionMoney'>
                            @error('promotionMoney')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label class="col-1 col-form-label ">Số tiền còn lại phải thu</label>
                        <label class="col-3 col-form-label ">{{ numberFormat($remainPaid) }} đ</label>

                        <label class="col-1 col-form-label ">Nhân viên thu tiền</label>
                        <label class="col-3 col-form-label">{{ $receptionName }}</label>

                    </div>
                    <div class="form-group row">
                        <label for="accountMoney" class="col-1 col-form-label ">TK thanh toán <span class="text-danger">*</span></label>
                        <div tabindex="1" class="col-3">
                            <select wire:model="accountMoney" name='accountMoney' id="accountMoney"
                                class="custom-select select2-box">
                                <option value=''>Chọn tài khoản</option>
                                @foreach ($accountMoneyList as $key => $item)
                                    <option value="{{ $item['id'] }}">
                                        {{ $item['account_number'] . ' - ' . $item['bank_name'] . ' (' . number_format($item['balance']) . ')' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('accountMoney')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>

                        <label for="customerDueDatePay" class="col-1 col-form-label ">Ngày hẹn trả</label>
                        <div class="col-3">
                            <input tabindex="5" id="customerDueDatePay" name="customerDueDatePay" type="date"
                                class="form-control" wire:model.lazy='customerDueDatePay'>
                            @error('customerDueDatePay')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="Note" class="col-1 col-form-label">Ghi chú <span class="text-danger">*</span></label>
                        <div class="col-3">
                            <textarea tabindex="6" class="form-control" id="Note" name="Note" rows="3" placeholder="Nhập ghi chú"
                                wire:model.lazy='customerNote'></textarea>
                            @error('customerNote')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>

                    </div>
                    <div class="form-group row">

                        <label for="TransactionDate" class="col-1 col-form-label ">Ngày thu <span class="text-danger">*</span></label>
                        <div class="col-3">
                            <input tabindex="7" id="customerDatePay" name="customerDatePay" type="date"
                                class="form-control" wire:model.lazy='customerDatePay'
                                max='{{ now()->format('Y-m-d') }}'>
                            @error('customerDatePay')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                </form>

                <div class="ibox-head p-0" style='display: block;'>
                    <div class="ibox-title pull-left">Danh sách đơn hàng chưa thanh toán</div>
                </div>
                <div class="row">
                    <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer mt-2">
                        <div class="row">
                            <div class="col-sm-12 table-responsive">
                                <table class="table table-striped table-bordered dataTable no-footer"
                                       id="category-table" cellspacing="0" width="100%" role="grid"
                                    aria-describedby="category-table_info" style="width: 100%;">
                                    <thead>
                                        <tr role="row">
                                            <th style="width: 20px;">
                                                <input type="checkbox" wire:model="checkAll" checked />
                                            </th>
                                            <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="ID: activate to sort column descending"
                                                style="width: 20px;">
                                                STT
                                            </th>
                                            <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                                style="width: 164.5px;">Tên khách hàng</th>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data as $key => $item)
                                            <tr data-parent="" data-index="1" role="row" class="odd">
                                                <td>
                                                    <input type="checkbox" name="checkOrder" wire:model="checkOrders"
                                                        value="{{ $item->id }}" class="check-box-order">
                                                </td>
                                                <td class="sorting_1">
                                                    {{ $key + 1 }}
                                                </td>
                                                <td>{{ $customerName }}</td>
                                                <td>{{ $customerAddress }}</td>
                                                <td>{{ numberFormat($item->total_money) }}</td>
                                                <td>
                                                    @if ($item->category == 2 && $item->type == 2)
                                                        @if ($item->payment_method == 1)
                                                            Bán lẻ xe máy
                                                        @endif
                                                        @if ($item->payment_method == 2)
                                                            Trả góp <br>
                                                            {{ $item->installment->contract_number }} <br>
                                                            {{ $item->installment->installmentCompany->company_name }}
                                                        @endif
                                                    @endif
                                                    @if ($item->category == 2 && $item->type == 1)
                                                        Bán buôn xe máy
                                                    @endif
                                                    @if ($item->category == 1 && $item->type == 2)
                                                        Bán lẻ phụ tùng
                                                    @endif
                                                    @if ($item->category == 1 && $item->type == 1)
                                                        Bán buôn phụ tùng
                                                    @endif
                                                    @if ($item->category == 3)
                                                        Kiểm tra định kỳ
                                                    @endif
                                                    @if ($item->category == 4)
                                                        Sửa chữa thông thường
                                                    @endif
                                                    @if ($item->category == 5)
                                                        Nợ cũ
                                                    @endif
                                                    @if ($item->category == 6)
                                                        Dịch vụ khác
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge badge-default">Chưa thanh toán</span>
                                                </td>
                                                <td class="text-danger">
                                                    {{ reFormatDate($item->date_payment, 'd-m-Y') }}
                                                </td>
                                                <td>
                                                    {{ reFormatDate($item->created_at, 'd-m-Y H:i:s') }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="text-center text-danger">
                                                <td colspan="9">Không có bản ghi</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tbody>
                                        @if(count($data) > 0)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td colspan="2">
                                                Tổng tiền
                                            </td>
                                            <td colspan="2">
                                            </td>
                                            <td>
                                                {{  numberFormat($sumMoney)}}
                                            </td>
                                            <td colspan="4">
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                {{-- @include('livewire.common.modal._modalDelete') --}}
                            </div>
                        </div>
                        {{-- @if (count($data) > 0)
                            {{ $data->links() }}
                        @endif --}}
                        <div class="form-group row mt-5">
                            <div class="col-12 text-center">
                                <button type="button" class="btn btn-primary" wire:click='createPhieuThu'
                                    wire:loading.attr="disabled">Tạo phiếu thu</button>
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
        $('#receiptType').on('change', function(e) {
            var data = $('#receiptType').select2("val");
            @this.set('receiptType', data);
            @this.set('showServiceType', false);
            if (parseInt(data) == 8) {
                @this.set('showServiceType', true);
            }
        });
        setSelect2Customer();
        let customerId = $('#customerPhone').data("customerId");
        let customerName = $('#customerPhone').data("customerName");
        let customerPhone = $('#customerPhone').data("customerPhone");
        if (customerId) {
            var $option = $("<option selected></option>").val(customerPhone).text(
                `${customerName} - ${customerPhone}`);
            $('#customerPhone').find('option')
                .remove()
                .end().append($option).trigger('change');
        }
        $('#serviceType').on('change', function(e) {
            var data = $('#serviceType').val();
            @this.set('serviceType', data);
        });
        $('#accountMoney').on('change', function(e) {
            var data = $('#accountMoney').val();
            @this.set('accountMoney', data);
        });
    })
    document.addEventListener('checkAllOrder', function() {
        $('.check-box-order').prop('checked', true);
    });

    document.addEventListener('setPayDatePicker', function() {
        setDatePickerUI();
    });
    document.addEventListener('triggerChangeCustomer', function(event) {
        let customerId = event.detail.customerId;
        let customerName = event.detail.customerName;
        let customerPhone = event.detail.customerPhone;
        if (customerId) {
            var $option = $("<option selected></option>").val(customerPhone).text(
                `${customerName} - ${customerPhone}`);
            $('#customerPhone').find('option')
                .remove()
                .end().append($option).trigger('change');
        }
    });
    document.addEventListener('select2Customer', function() {
        setSelect2Customer();
    });
    document.addEventListener('livewire:load', function() {
        setDatePickerUI();
    });



    function setSelect2Customer() {
        let ajaxUrl = $('#customerPhone').data("ajaxUrl");
        $('#customerPhone').select2({
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

        $('#customerPhone').on('change', function(e) {
            var data = $('#customerPhone').select2("val");
            @this.set('customerPhone', data);
        });
    };

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
        $("#customerDueDatePay").kendoDatePicker({
            format: "dd/MM/yyyy"
        });
        var customerDueDatePay = $("#customerDueDatePay").data("kendoDatePicker");
        customerDueDatePay.min(new Date());
        customerDueDatePay.bind("change", function() {
            var value = this.value();
            if (value != null) {
                var datestring = moment(value).format('YYYY-MM-DD');
                @this.set('customerDueDatePay', datestring);
            }
        });
    };
    document.addEventListener('confirmPrintPdf', function(event) {
        let titleMessage = event.detail.point > 0 ? 'Thêm mới phiếu thu thành công.Khách hàng ' + event
            .detail
            .customer + ' được cộng ' + event.detail.point +
            ' điểm. Bạn có muốn in phiếu thu bằng pdf không?' :
            'Thêm mới phiếu thu thành công. Bạn có muốn in phiếu thu bằng pdf không?';
        Swal.fire({
            title: titleMessage,
            icon: 'success',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy bỏ',
            showCancelButton: true,
            showCloseButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                @this.emit('printfPDF');
            }
        })
    });
    document.addEventListener('redirectToPrintfPdf', function(event) {
        let url = event.detail.url;
        window.open(
            url,
            '_blank'
        );
    });
</script>
