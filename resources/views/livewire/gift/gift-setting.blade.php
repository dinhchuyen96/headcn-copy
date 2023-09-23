<div>
    <div class="page-heading">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fa fa-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Thông tin quà tặng</li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div wire:loading class="loader"></div>
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thông tin quà tặng</div>
            </div>
            <div class="ibox-body">
                <form>
                    @csrf
                    <div class="text-info text-center">
                        <i class="fa fa-info-circle" aria-hidden="true"></i> Module tính điểm cho khách hàng theo công
                        thức <br>
                        <strong class="text-danger">Sửa chữa = {{ $giftTranferItem->gift_tranfer }}/1diem, Phụ tùng = {{ $giftTranferItem->gift_tranfer }}/1diem, xe = NA/1diem </strong>
                    </div>
                    <div class="form-group row pt-3">
                        <div class="col-3"></div>
                        <label for="giftTranfer" class="col-2 col-form-label ">Số tiền tương ứng 1 điểm<span
                                class="text-danger">*</span></label>
                        <div class="col-4">
                            <input id="giftTranfer" type="text" class="form-control"
                                onkeypress="return onlyNumberKey(event)" wire:model.defer="giftTranfer">
                            @error('giftTranfer')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row justify-content-center btn-group-mt">
                        <div class="col-1">

                            <button wire:click.prevent="store" type="button" class="btn btn-primary"><i
                                    class="fa fa-save"></i> Cập nhật</button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
