<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">Bán lẻ</div>
        </div>
        <div class="ibox-body">
            <form>
                <div class="form-group row mt-1">
                    <label for="PhoneNumber" class="col-1 col-form-label">Số điện thoại <span
                            class="text-danger">*</span></label>
                    <div class="col-3">
                        <input id="PhoneNumber" name="PhoneNumber"
                            placeholder="Số điện thoại" type="number" wire:model.lazy='phone'
                            {{ $status ? 'disabled' : '' }} class="form-control form-red" required="required">
                        @error('phone')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                    <label for="Birthday" class="col-1 col-form-label">Ngày sinh <span
                            class="text-danger">*</span></label>
                    <div class="col-3">
                        <input type="date" id="birthdayDate" class="form-control date-picker input-date-kendo"
                            max='{{ date('Y-m-d') }}' {{ $status ? 'disabled' : '' }} wire:model.lazy="birthday">
                        @error('birthday')
                            @include('layouts.partials.text._error')
                        @enderror

                    </div>
                    <label for="CustomerName" class="col-1 col-form-label">Tên khách hàng <span
                            class="text-danger">*</span></label>
                    <div class="col-3">
                        <input id="CustomerName" name="CustomerName" placeholder="Tên khách hàng" type="text"
                            wire:model.lazy='name' class="form-control" required="required"
                            {{ $status ? 'disabled' : '' }}>
                        @error('name')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                </div>

                <div class="form-group row">

                    <label for="Email" class="col-1 col-form-label">Email </label>
                    <div class="col-3">
                        <input id="Email" name="Email" placeholder="Email" type="text" wire:model.lazy='email'
                            class="form-control" {{ $status ? 'disabled' : '' }}>
                    </div>
                    <label for="CustomerName" class="col-1 col-form-label">Địa chỉ<span
                                class="text-danger"> *</span></label>
                        <div class="col-3">
                            <input id="address" name="address" placeholder="Địa chỉ" type="text"
                                wire:model.lazy='address' class="form-control" required="required"
                                {{ $status ? 'disabled' : '' }}>
                            @error('address')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="CustomerDistrict" class="col-1 col-form-label ">Thành phố/ Tỉnh<span
                                class="text-danger"> *</span></label>
                        <div class="col-3">
                            <select wire:model="province_id" id="supplyProvince" class="form-control select2-box"
                                {{ $status ? 'disabled' : '' }}>
                                <option hidden>Chọn Thành phố/ Tỉnh</option>
                                @foreach ($province as $key => $item)
                                    <option value="{{ $key }}" {{ $key == $province_id ? 'selected' : '' }}>
                                        {{ $item }}</option>
                                @endforeach
                            </select>
                            @error('province_id')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                </div>
                <div>
                    <div wire:loading class="loader"></div>
                    <div class="form-group row">
                        <label for="CustomerProvince" class="col-1 col-form-label ">Quận/ Huyện<span
                                class="text-danger"> *</span></label>
                        <div class="col-3">
                            <select wire:model="district_id" id="supplyDistrict" class="custom-select select2-box"
                                {{ $status ? 'disabled' : '' }}>
                                <option hidden>Chọn Quận/ Huyện</option>
                                @foreach ($district as $key => $item)
                                    <option value="{{ $key }}"
                                        {{ $key == $district_id ? 'selected' : '' }}>
                                        {{ $item }}</option>
                                @endforeach
                            </select>
                            @error('district_id')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>

                        <label for="CustomerProvince" class="col-1 col-form-label ">Phường/ Xã <span
                                class="text-danger"> *</span></label>
                        <div class="col-3">
                            <select wire:model="ward_id" id="supplyWard" class="custom-select select2-box"
                                {{ $status ? 'disabled' : '' }}>
                                <option hidden>Chọn Phường/ Xã</option>
                                @foreach ($ward as $key => $item)
                                    <option value="{{ $key }}" {{ $key == $ward_id ? 'selected' : '' }}>
                                        {{ $item }}</option>
                                @endforeach
                            </select>
                            @error('ward_id')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="Sex" class="col-1 col-form-label">Giới tính </label>
                    <div class="col-3">
                        <select class="form-control" wire:model.lazy='sex' {{ $status ? 'disabled' : '' }}>
                            <option value="1">Nam</option>
                            <option value="2">Nữ</option>
                        </select>
                    </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="Job" class="col-1 col-form-label">Nghề nghiệp <span
                            class="text-danger">*</span></label>
                    <div class="col-3">
                        <input id="Job" placeholder="Nghề nghiệp" type="text" wire:model.lazy='job'
                            class="form-control" {{ $status ? 'disabled' : '' }}>
                        @error('job')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                    <label for="sellerId" class="col-1 col-form-label ">NV bán hàng</label>
                    <div class="col-3">
                        <select id="sellerId" name="sellerId" {{ $status ? 'disabled' : '' }}
                            wire:model.lazy="sellerId" class="custom-select select2-box form-control">
                            <option value="">--Chọn--</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}
                                </option>
                            @endforeach
                        </select>

                    </div>

                    <label for="transactionDate" class="col-1 col-form-label">Ngày bán 
                        {{-- <span class="text-danger">*</span> --}}
                    </label>
                    <div class="col-3">
                        <input type="date" id="transactionDate" class="form-control date-picker input-date-kendo"
                            max='{{ date('Y-m-d') }}' {{ $status ? 'disabled' : '' }} wire:model.lazy="transactionDate">
                        @error('birthday')
                            @include('layouts.partials.text._error')
                        @enderror

                    </div>
                    <label for="technicalId" class="col-1 col-form-label ">NV kĩ thuật</label>
                    <div class="col-3">
                        <select id="technicalId" name="technicalId" wire:model.lazy="technicalId"
                            {{ $status ? 'disabled' : '' }} class="custom-select select2-box form-control">
                            <option value="">--Chọn--</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="paymentMethod" class="col-1 col-form-label">Phương thức T   T <span
                            class="text-danger">*</span></label>
                    <div class="col-3">
                        <select id="paymentMethod" class="form-control select2-box" wire:model='paymentMethod'
                            {{ $status ? 'disabled' : '' }}>
                            <option value="1">Thanh toán trực tiếp</option>
                            <option value="2">Trả góp</option>
                        </select>
                    </div>
                    <label for="BarCode" class="col-md-1 col-form-label">Barcode</label>
                    <div class="col-md-3">
                        <input id="BarCode" wire:model.lazy="barCode" placeholder="Barcode xe" type="text"
                            class="form-control" {{ $status ? 'disabled' : '' }}>
                        @error('barCode')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-1 virtual text-left">
                        <input type='checkbox' {{ $order_id ? 'disabled' : '' }} style="margin: 10px 0px"
                            id='isVirtual' wire:model="isVirtual"> Đơn ảo
                    </div>
                    <div class="col-md-2"> <button {{ $status ? 'disabled' : '' }} type="button"
                            wire:click="addBarCode('{{ $barCode }}')" style="font-size: 16px;" class="btn btn-info add-new "><i
                                class="fa fa-search"></i> SCAN </button></div>
                </div>

                <div class="{{ $paymentMethod == 2 && !$order_id ? '' : 'd-none' }}">
                    <div class="form-group row">
                        <label for="contractCode" class="col-1 col-form-label">Số hợp đồng<span
                                class="text-danger"> *</span></label>
                        <div class="col-3">
                            <input id="contractCode" placeholder="Số hợp đồng" type="text"
                                wire:model.lazy='contractCode' class="form-control"
                                {{ $status ? 'disabled' : '' }}>
                            @error('contractCode')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="installmentCompany" class="col-1 col-form-label">Công ty tài chính<span
                                class="text-danger"> *</span></label>
                        <div class="col-3">
                            <select wire:model="installmentCompany" id="installmentCompany"
                                class="custom-select select2-box" {{ $status ? 'disabled' : '' }}>
                                <option value="">Chọn công ty tài chính</option>
                                @foreach ($installmentCompanyList as $key => $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->company_name }}</option>
                                @endforeach
                            </select>
                            @error('installmentCompany')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="installmentMoney" class="col-1 col-form-label">Số tiền trả góp<span
                                class="text-danger"> *</span></label>
                        <div class="col-3">
                            <input id="installmentMoney" placeholder="Số tiền trả góp" type="text"
                                wire:model.lazy='installmentMoney' class="form-control"
                                onkeypress="return onlyNumberKey(event)" {{ $status ? 'disabled' : '' }}>
                            @error('installmentMoney')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <div class="table-wrapper" style="padding-top: 0; margin-top: 0;">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-8">

                            </div>
                            <div class="col-sm-4 text-right">
                                <a href="{{ route('xemay.dichvukhac.index') }}" class="btn btn-info">
                                    <i class="fa fa-bars"></i> Dịch vụ khác
                                </a>
                                <button type="button" wire:click="add()" class="btn btn-info"
                                    @if (!$addBtn) disabled @endif
                                    {{ $status ? 'hidden' : '' }}><i class="fa fa-plus"></i>
                                    CHỌN XE</button>
                            </div>
                        </div>
                    </div>
                    @livewire('component.list-input-motorbike', ['type'=>2])
                </div>
            </div>
            <div class="form-group row">
                <div class="col-12 text-center">
                    <button {{ $status ? 'hidden' : '' }} name="submit" type="submit" wire:click='store'
                        class="btn btn-primary">
                        @if ($order_id)
                            Cập nhật hóa đơn
                        @else
                            Tạo hóa đơn bán lẻ
                        @endif
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@section('js')
    <script type="text/javascript">
        window.livewire.on('close-modal-import', () => {
            document.getElementById('modal-form-import').click();
        })
        document.addEventListener('livewire:load', function() {
            $(function() {
                $("#BarCode").on('keyup', function(e) {
                    if (e.key === 'Enter' || e.keyCode === 13) {
                        window.livewire.emit('addBarCode', document.getElementById('BarCode')
                            .value);
                    }

                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            $('#supplyProvince').on('change', function(e) {
                var data = $('#supplyProvince').select2("val");
                @this.set('province_id', data);
            });
            $('#supplyDistrict').on('change', function(e) {
                var data = $('#supplyDistrict').select2("val");
                @this.set('district_id', data);
            });
            $('#supplyWard').on('change', function(e) {
                var data = $('#supplyWard').select2("val");
                @this.set('ward_id', data);
            });
            $('#paymentMethod').on('change', function(e) {
                var data = $('#paymentMethod').select2("val");
                @this.set('paymentMethod', data);
            });
            $('#installmentCompany').on('change', function(e) {
                var data = $('#installmentCompany').select2("val");
                @this.set('installmentCompany', data);
            });
            $('#sellerId').on('change', function(e) {
                var data = $('#sellerId').select2("val");
                @this.set('sellerId', data);
            });
            $('#technicalId').on('change', function(e) {
                var data = $('#technicalId').select2("val");
                @this.set('technicalId', data);
            });
        });
        document.addEventListener('redirectToOtherService', function(event) {
            let titleMessage =
                'Tạo mới thành công. Bạn có muốn chuyển tới làm dịch vụ khách cho khách hàng ' + event
                .detail
                .customer +
                ' không?';
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
@endsection
