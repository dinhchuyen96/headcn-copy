<div>
    <div wire:loading class="loader"></div>

    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thông tin phiếu sửa chữa</div>
            </div>
            <div class="ibox-body">
                <div>
                    <div class="form-group row mt-5">
                        <label for="serviceRequest" class="col-2 col-form-label ">Triệu chứng/Yêu cầu KT
                            <i class="fa fa-asterisk" aria-hidden="true"></i></label>
                        <div class="col-4">
                            <textarea class="form-control" id="serviceRequest" name="serviceRequest" rows="4"
                                wire:model.lazy="serviceRequest"></textarea>
                            @error('serviceRequest')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="serviceRequest" class="col-2 col-form-label ">Tư vấn sửa chữa</label>
                        <div class="col-3">
                            <textarea class="form-control" id="contentSuggest" name="contentSuggest" rows="4"
                                wire:model.lazy="contentSuggest"></textarea>
                        </div>
                        <div class="col-1">
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="beforeRepair"
                                    wire:model="beforeRepair">
                                <label class="form-check-label" for="beforeRepair">Trước sửa chữa</label>
                            </div>
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="afterRepair"
                                    wire:model="afterRepair">
                                <label class="form-check-label" for="afterRepair">Sau sửa chữa</label>
                            </div>

                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="notNeedWash"
                                    wire:model="notNeedWash">
                                <label class="form-check-label" for="notNeedWash">Không cần rửa xe</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ServiceRequestCode" class="col-2 col-form-label ">Mã SR
                            <i class="fa fa-asterisk" aria-hidden="true"></i>
                        </label>
                        <div class="col-4">
                            <input id="serviceRequestCode" name="serviceRequestCode" type="text" class="form-control"
                                wire:model.lazy="serviceRequestCode">
                            @error('serviceRequestCode')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="ServiceType" class="col-2 col-form-label ">Loại sửa chữa
                            <i class="fa fa-asterisk" aria-hidden="true"></i>
                        </label>
                        <div class="col-4">
                            <select id="serviceType" name="serviceType" class="custom-select select2-box form-control"
                                wire:model="serviceType">
                                @foreach ($serviceTypeList as $serviceType)
                                    <option value="{{ $serviceType->id }}">{{ $serviceType->name }}</option>
                                @endforeach
                            </select>
                            @error('serviceType')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="serviceUserCheckId" class="col-2 col-form-label ">Người kiểm tra</label>
                        <div class="col-4">
                            <select id="serviceUserCheckId" name="serviceUserCheckId" wire:model="serviceUserCheckId"
                                class="custom-select select2-box form-control">
                                <option value="">--Chọn--</option>
                                @foreach ($inspectionStaffs as $inspectionStaff)
                                    <option value="{{ $inspectionStaff->id }}">{{ $inspectionStaff->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('serviceUserCheckId')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="serviceUserId" class="col-2 col-form-label ">Người tiếp nhận<i
                                class="fa fa-asterisk" aria-hidden="true"></i></label>
                        <div class="col-4">
                            <select id="serviceUserId" name="serviceUserId" wire:model="serviceUserId"
                                class="custom-select select2-box form-control">
                                @foreach ($technicalStaffs as $technicalStaff)
                                    <option value="{{ $technicalStaff->id }}">{{ $technicalStaff->name }}</option>
                                @endforeach
                            </select>
                            @error('serviceUserId')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="numberMotor" class="col-2 col-form-label ">Biển số</label>
                        <div class="col-4">
                            <input id="numberMotor" name="numberMotor" type="text" class="form-control"
                                wire:model.lazy="numberMotor">
                        </div>
                        <label for="km" class="col-2 col-form-label ">Số KM</label>
                        <div class="col-4">
                            <input id="km" name="km" type="number" class="form-control" wire:model.lazy="km">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="serviceFixerId" class="col-2 col-form-label ">Nhân viên sửa chữa chính</label>
                        <div class="col-4">
                            <select id="serviceFixerId" name="serviceFixerId" wire:model.lazy="serviceFixerId"
                                class="custom-select select2-box form-control">
                                <option value="">--Chọn--</option>
                                @foreach ($listFixer as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('serviceFixerId')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="exportWarehouseId" class="col-2 col-form-label ">Nhân viên xuất kho</label>
                        <div class="col-4">
                            <select id="exportWarehouseId" name="exportWarehouseId" wire:model.lazy="exportWarehouseId"
                                class="custom-select select2-box form-control">
                                <option value="">--Chọn--</option>
                                @foreach ($listExporter as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('exportWarehouseId')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="check_date" class="col-2 col-form-label ">Ngày KT</label>
                        <div class="col-4">
                            <input type="date" id="check_date"
                                class="form-control" wire:model.lazy="checkDate">
                        </div>
                    </div>
                    <div>
                        <table style="width: 100%;text-align: center; margin-top:2.5rem" border="1">
                            <tbody>
                                <tr>
                                    <td colspan="12"><strong>Kiểm tra phụ tùng tại quầy tiếp nhận</strong></td>
                                    <td colspan="12"><strong>Kiểm tra phụ tùng tại xưởng dịch vụ</strong></td>
                                </tr>
                                <tr>
                                    <td rowspan="2">Phụ tùng</td>
                                    <td colspan="5">Kiểm tra (✔)</td>
                                    <td rowspan="2">Phụ tùng</td>
                                    <td colspan="5">Kiểm tra (✔)</td>
                                    <td rowspan="2">Phụ tùng</td>
                                    <td colspan="5">Kiểm tra (✔)</td>
                                    <td rowspan="2">Phụ tùng</td>
                                    <td colspan="5">Kiểm tra (✔)</td>
                                </tr>
                                <tr>
                                    <td>O</td>
                                    <td>D</td>
                                    <td>T</td>
                                    <td>V</td>
                                    <td>B</td>
                                    <td>O</td>
                                    <td>D</td>
                                    <td>T</td>
                                    <td>V</td>
                                    <td>B</td>
                                    <td>O</td>
                                    <td>D</td>
                                    <td>T</td>
                                    <td>V</td>
                                    <td>B</td>
                                    <td>O</td>
                                    <td>D</td>
                                    <td>T</td>
                                    <td>V</td>
                                    <td>B</td>
                                </tr>
                                <tr style="text-align: left;">
                                    <td>Dầu phanh</td>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Lốp trước</td>
                                    @for ($i = 6; $i <= 10; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Dây phanh</td>
                                    @for ($i = 11; $i <= 15; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Côn</td>
                                    @for ($i = 16; $i <= 20; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                </tr>
                                <tr style="text-align: left;">
                                    <td>Phanh trước</td>
                                    @for ($i = 21; $i <= 25; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Lốp sau</td>
                                    @for ($i = 26; $i <= 30; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Dầu số</td>
                                    @for ($i = 31; $i <= 35; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Chổi than</td>
                                    @for ($i = 36; $i <= 40; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                </tr>
                                <tr style="text-align: left;">
                                    <td>Phanh sau</td>
                                    @for ($i = 41; $i <= 45; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Dầu máy</td>
                                    @for ($i = 46; $i <= 50; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Dây đai</td>
                                    @for ($i = 51; $i <= 55; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Họng ga</td>
                                    @for ($i = 56; $i <= 60; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                </tr>
                                <tr style="text-align: left;">
                                    <td>Bóng đèn</td>
                                    @for ($i = 61; $i <= 65; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Làm mát</td>
                                    @for ($i = 66; $i <= 70; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Ắc quy</td>
                                    @for ($i = 71; $i <= 75; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Bugi</td>
                                    @for ($i = 76; $i <= 80; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                </tr>
                                <tr style="text-align: left;">
                                    <td>Công tắc</td>
                                    @for ($i = 81; $i <= 85; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Xích</td>
                                    @for ($i = 86; $i <= 90; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Lọc gió</td>
                                    @for ($i = 91; $i <= 95; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr style="text-align: left;">
                                    <td>Còi</td>
                                    @for ($i = 96; $i <= 100; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Công tơ mét</td>
                                    @for ($i = 101; $i <= 105; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Nhông xích</td>
                                    @for ($i = 106; $i <= 110; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group row pt-4">
                        <label for="resultRepair" class="col-2 col-form-label ">Ghi chú sau KT</label>
                        <div class="col-10">
                            <textarea class="form-control" id="resultRepair" name="resultRepair" rows="4"
                                wire:model.lazy="resultRepair"></textarea>
                            @error('resultRepair')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>

                    </div>
                    <div class="form-group row pt-4">
                        <label for="repairHistory" class="col-2 col-form-label ">Lịch sử sửa chữa</label>
                        <div class="col-10">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width:10%">STT</th>
                                        <th style="width:30%">Ngày giờ KT</th>
                                        <th style="width:60%">Ghi chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($repairNoteHistory as $key => $itemHistory)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $itemHistory->in_factory_date }}</td>
                                            <td>{{ $itemHistory->result_repair }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-danger"> Chưa có dữ liệu</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-4 font-weight-bold border bg-light p-3">Thông tin xe</div>
                        <div class="col-8 font-weight-bold border bg-light p-3">Thông tin khách hàng</div>
                    </div>
                    <div class="row mt-3">
                        <label for="chassicNo" class="col-1 col-form-label ">Số khung<i class="fa fa-asterisk"
                                aria-hidden="true"></i></label>
                        <div class="col-3">
                            <input id="chassicNo" name="chassicNo" type="text" class="form-control"
                                {{ $isOut ? '' : 'readonly' }} wire:model.lazy="chassicNo">
                            @error('chassicNo')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="customerName" class="col-1 col-form-label ">Họ tên <i class="fa fa-asterisk"
                                aria-hidden="true"></i></label>
                        <div class="col-3">
                            <input id="customerName" name="customerName" type="text" class="form-control"
                                wire:model.lazy="customerName">
                            @error('customerName')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="customerAddress" class="col-1 col-form-label ">Địa chỉ </label>
                        <div class="col-3">
                            <input id="customerAddress" name="customerAddress" type="text" class="form-control"
                                wire:model.lazy="customerAddress">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <label for="engineNo" class="col-1 col-form-label ">Số máy<i class="fa fa-asterisk"
                                aria-hidden="true"></i></label>
                        <div class="col-3">
                            <input id="engineNo" name="engineNo" type="text" class="form-control"
                                {{ $isOut ? '' : 'readonly' }} wire:model.lazy="engineNo">
                            @error('engineNo')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="customerPhone" class="col-1 col-form-label ">SĐT <i class="fa fa-asterisk"
                                aria-hidden="true"></i></label>
                        <div class="col-3">
                            <input id="customerPhone" name="customerPhone" type="text" class="form-control"
                                wire:model.lazy="customerPhone">
                            @error('customerPhone')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="customerCity" class="col-1 col-form-label ">Thành phố/ Tỉnh </label>
                        <div class="col-3">
                            <select name="customerCity" wire:model="customerCity" id="customerCity"
                                class="custom-select form-control select2-box">
                                <option value="">--Chọn--</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->province_code }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="row mt-3">
                        <label for="buyDate" class="col-1 col-form-label ">Ngày mua<i class="fa fa-asterisk"
                                aria-hidden="true"></i></label>
                        <div class="col-3">
                            <input type="date" id="buyDate" class="form-control" max='{{ date('Y-m-d') }}'
                                {{ $isOut ? '' : 'disabled' }} wire:model.lazy="buyDate">

                            @error('buyDate')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="customerSex" class="col-1 col-form-label ">Giới tính</label>
                        <div class="col-3">
                            <select id="customerSex" name="customerSex" type="text" class="form-control select2-box"
                                wire:model="customerSex">
                                <option value="">--Chọn--</option>
                                @foreach ($sexList as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <label for="customerDistrict" class="col-1 col-form-label ">Quận/ Huyện </label>
                        <div class="col-3">
                            <select name="customerDistrict" wire:model="customerDistrict" id="customerDistrict"
                                class="custom-select form-control select2-box">
                                <option value="">--Chọn--</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district->district_code }}">{{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-4">
                        </div>
                        <label class="col-1 col-form-label ">Tích điểm</label>
                        <label class="col-4 col-form-label text-info">{{ $customerPoint }} điểm</label>
                    </div>
                    @livewire('component.input-repair',['isEdit'=>true,'orderId'=>$orderId,'mainFixerId'=>$serviceFixerId])
                    @livewire('component.input-accessories',['isEdit'=>true,'orderId'=>$orderId])
                    <div class="form-group row pt-3">
                        <div class="col-12 text-center">
                            <button name="button" {{ $isDisableAccesory || $isDisableTask ? 'disabled' : '' }}
                                wire:click.prevent="update" type="submit" class="btn btn-primary">Cập
                                nhật</button>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        $('#customerDistrict').on('change', function(e) {
            var data = $('#customerDistrict').select2("val");
            @this.set('customerDistrict', data);
        });
        $('#customerCity').on('change', function(e) {
            var data = $('#customerCity').select2("val");
            @this.set('customerCity', data);
        });
        $('#serviceUserId').on('change', function(e) {
            var data = $('#serviceUserId').select2("val");
            @this.set('serviceUserId', data);
        });
        $('#serviceUserCheckId').on('change', function(e) {
            var data = $('#serviceUserCheckId').select2("val");
            @this.set('serviceUserCheckId', data);
        });

        $('#serviceType').on('change', function(e) {
            var data = $('#serviceType').select2("val");
            @this.set('serviceType', data);
        });
        $('#customerSex').on('change', function(e) {
            var data = $('#customerSex').select2("val");
            @this.set('customerSex', data);
        });
        $('#serviceFixerId').on('change', function(e) {
            var data = $('#serviceFixerId').select2("val");
            @this.set('serviceFixerId', data);
            if (data != null) {
                window.livewire.emit('setServiceFixerId', data);
            }
        });
        $('#exportWarehouseId').on('change', function(e) {
            var data = $('#exportWarehouseId').select2("val");
            @this.set('exportWarehouseId', data);
        });
    });

    document.addEventListener('setTranferDatePicker', function() {
        setDatePickerUI();
    });
    document.addEventListener('livewire:load', function() {
        setDatePickerUI();
    });

    function setDatePickerUI() {
        $("#buyDate").kendoDatePicker({
            format: "dd/MM/yyyy"
        });
        var buyDate = $("#buyDate").data("kendoDatePicker");
        buyDate.max(new Date());
        buyDate.bind("change", function() {
            var value = this.value();
            if (value != null) {
                var datestring = moment(value).format('YYYY-MM-DD');
                @this.set('buyDate', datestring);
            }
        });

        $("#check_date").kendoDatePicker({
            max: new Date(),
            format: 'dd/MM/yyyy',
            change: function() {
                if (this.value() != null) {
                    window.livewire.emit('setCheckDate', {
                        ['check_date']: this.value() ? this.value().toLocaleDateString(
                            'en-US') : null
                    });
                }
            }
        });
    };
    document.addEventListener('confirmPrintPdf', function(event) {
        let titleMessage =
            "Cập nhật phiếu sửa chữa thông thường thành công.Bạn có muốn in phiếu thu bằng pdf không?";
        Swal.fire({
            title: titleMessage,
            icon: 'success',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy bỏ',
            showCancelButton: true,
            showCloseButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = event.detail.urlPrintf;
            }
        })
    });
</script>
