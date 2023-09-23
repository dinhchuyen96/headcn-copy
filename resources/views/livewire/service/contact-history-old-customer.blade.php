<div>
    <div class="page-content fade-in-up">
        <div wire:loading class="loader"></div>
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Lịch sử liên hệ</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row mt-1">
                        <label for="CustomerName" class="col-2 col-form-label">Tên khách hàng</label>
                        <div class="col-4">
                            {{ $customer->name }}
                        </div>
                        <label for="CustomerCode" class="col-2 col-form-label ">Mã khách hàng <span
                                class="text-danger" {{ checkRoute('show') ? 'hidden' : '' }}>*</span></label>
                        <div class="col-4">
                            {{ $customer->code }}
                        </div>
                    </div>

                    <div class="form-group row mt-1">
                        <label for="Sex" class="col-2 col-form-label">Giới tính</label>
                        <div class="col-4">
                            @if (!empty($customer->sex))
                                {{ $customer->sex == 1 ? 'Nam' : 'Nữ' }}
                            @endif
                        </div>
                        <label for="Birthday" class="col-2 col-form-label ">Ngày sinh</label>
                        <div class="col-4">
                            {{ $customer->birthday }}
                        </div>
                    </div>
                    <div class="form-group row mt-1">
                        <label for="PhoneNumber" class="col-2 col-form-label ">Số điện thoại</label>
                        <div class="col-4">
                            {{ $customer->phone }}
                        </div>
                        <label for="Address" class="col-2 col-form-label ">Địa chỉ</label>
                        <div class="col-4">
                            {{ $customer->address . (isset($customer->wardCustomer) ? ', ' . $customer->wardCustomer->name : '') . (isset($customer->districtCustomer) ? ', ' . $customer->districtCustomer->name : '') . (isset($customer->provinceCustomer) ? ', ' . $customer->provinceCustomer->name : '') }}
                        </div>

                    </div>

                    <div class="form-group row">
                        <label for="IdentityCode" class="col-2 col-form-label ">Số CMT/CCCD</label>
                        <div class="col-4">
                            {{ $customer->identity_code ?? '-' }}
                        </div>
                        <label for="Job" class="col-2 col-form-label ">Nghề nghiệp</label>
                        <div class="col-4">
                            {{ $customer->job ?? '-' }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Email" class="col-2 col-form-label ">Email</label>
                        <div class="col-4">
                            {{ $customer->email ?? '-' }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="contactMethod" class="col-2 col-form-label ">Hình thức liên hệ <span
                                class="text-danger" {{ checkRoute('show') ? 'hidden' : '' }}>*</span></label>
                        <div class="col-4">
                            <select id="contactMethod" name="contactMethod" wire:model.lazy="contactMethod"
                                class="custom-select select2-box form-control">
                                <option value="">--Chọn--</option>
                                @foreach ($contactMethodList as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            @error('contactMethod')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="contactDate" class="col-2 col-form-label ">Ngày liên hệ<span
                                class="text-danger" {{ checkRoute('show') ? 'hidden' : '' }}>*</span></label>
                        <div class="col-4">
                            <input id="contactDate" name="contactDate" type="date" class="form-control"
                                wire:model.lazy='contactDate'>
                            @error('contactDate')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="note" class="col-2 col-form-label">Ghi chú<span
                                class="text-danger" {{ checkRoute('show') ? 'hidden' : '' }}>*</span></label>
                        <div class="col-4">
                            <textarea tabindex="6" class="form-control" id="note" name="note" rows="3"
                                placeholder="Nhập ghi chú" wire:model.lazy='note'></textarea>
                            @error('note')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row justify-content-center btn-group-mt">
                        <div>
                            <a href="{{ route('cskh.dich-vu-cham-soc-khach-hang.index') }}" class="btn btn-default">
                                <i class="fa fa-arrow-left"></i>
                                Trở lại
                            </a>
                            <button wire:click.prevent="store" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Thêm lịch sử liên hệ
                            </button>
                        </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered dataTable no-footer" id="category-table"
                    cellspacing="0" width="100%" role="grid" aria-describedby="category-table_info" style="width: 100%;">
                    <thead>
                        <tr role="row">
                            <th tabindex="0" aria-controls="category-table" aria-sort="ascending"
                                aria-label="ID: activate to sort column descending" style="width: 50.5px;">STT
                            </th>
                            <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1" style="width: 164.5px;">
                                Hình thức liên hệ</th>
                            <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1" style="width: 164.5px;">
                                Ngày liên hệ</th>
                            <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1" style="width: 164.5px;">
                                Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contactHistories as $key => $item)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $item->contactMethod->method_name ?? '' }}</td>
                                <td>{{ $item->date_contact ?? '' }}</td>
                                <td>{{ $item->note ?? '' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-danger">Chưa có lịch sử liên hệ</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#contactMethod').on('change', function(e) {
            var data = $('#contactMethod').select2("val");
            @this.set('contactMethod', data);
        });

    })
    document.addEventListener('setContactDatePicker', function() {
        setDatePickerUI();
    });
    document.addEventListener('livewire:load', function() {
        setDatePickerUI();
    });

    function setDatePickerUI() {
        $("#contactDate").kendoDatePicker({
            format: "dd/MM/yyyy"
        });
        var customerDatePay = $("#contactDate").data("kendoDatePicker");
        customerDatePay.max(new Date());
        customerDatePay.bind("change", function() {
            var value = this.value();
            if (value != null) {
                var datestring = moment(value).format('YYYY-MM-DD');
                @this.set('contactDate', datestring);
            }
        });
    };
</script>
