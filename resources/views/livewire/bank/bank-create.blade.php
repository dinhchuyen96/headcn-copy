<div>
    <div class="page-content fade-in-up">
        <div wire:loading class="loader"></div>
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thông tin tài khoản ngân hàng</div>
            </div>
            <div class="ibox-body">
                <form>
                    @csrf
                    <div class="form-group row">
                        <label for="accountCode" class="col-1 col-form-label ">Mã tài khoản<span
                                class="text-danger"> *</span></label>
                        <div class="col-3">
                            <input id="accountCode" placeholder="Mã tài khoản" type="text" class="form-control"
                                wire:model.defer="accountCode">
                            @error('accountCode')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="accountNumber" class="col-1 col-form-label ">Số tài khoản<span
                                class="text-danger"> *</span></label>
                        <div class="col-3">
                            <input id="accountNumber" placeholder="Số tài khoản" type="text" class="form-control"
                                wire:model.defer="accountNumber">
                            @error('accountNumber')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="accountOwner" class="col-1 col-form-label ">Chủ tài khoản<span
                                class="text-danger"> *</span></label>
                        <div class="col-3">
                            <input id="accountOwner" placeholder="Chủ tài khoản" type="text" class="form-control"
                                wire:model.defer="accountOwner">
                            @error('accountOwner')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        
                        <label for="accountType" class="col-1 col-form-label ">Loại tài khoản<span
                                class="text-danger"> *</span></label>
                        <div class="col-3">
                            <select wire:model="accountType" name='accountType' id="accountType"
                                class="custom-select select2-box">
                                <option value=''>Chọn loại TK</option>
                                <option value="CASH">CASH</option>
                                <option value="BANK">BANK</option>
                            </select>
                            @error('accountType')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="bankName" class="col-1 col-form-label ">Tên ngân hàng<span
                                class="text-danger"> *</span></label>
                        <div class="col-3">
                            <input id="bankName" placeholder="Tên ngân hàng" type="text" class="form-control"
                                wire:model.defer="bankName">
                            @error('bankName')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="balance" class="col-1 col-form-label ">Số tiền<span
                                class="text-danger"> *</span></label>
                        <div class="col-3">
                            <input id="balance" placeholder="Số tiền ban đầu" type="text" class="form-control"
                                onkeypress="return onlyNumberKey(event)" wire:model.defer="balance">
                            @error('balance')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row justify-content-center btn-group-mt">
                        <div>
                            <a href="{{ route('bank.index') }}" class="btn btn-default">
                                <i class="fa fa-arrow-left"></i>
                                Trở lại
                            </a>
                            <button wire:click.prevent="store" type="button" class="btn btn-primary"><i
                                    class="fa fa-plus"></i> Tạo mới</button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@section('js')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            $('#accountType').on('change', function(e) {
                var data = $('#accountType').select2("val");
                @this.set('accountType', data);
            });
        });
    </script>
@endsection
