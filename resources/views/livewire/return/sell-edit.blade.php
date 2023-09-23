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
                    <div class="form-group row">
                        <label for="giftName" class="col-1 col-form-label ">Tên quà tặng<span
                                class="text-danger"> *</span></label>
                        <div class="col-5">
                            <input id="giftName" type="text" class="form-control" wire:model.defer="giftName">
                            @error('giftName')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="giftPoint" class="col-1 col-form-label ">Điểm quà tặng<span
                                class="text-danger"> *</span></label>
                        <div class="col-5">
                            <input id="giftPoint" type="text" class="form-control"
                                onkeypress="return onlyNumberKey(event)" wire:model.defer="giftPoint">
                            @error('giftPoint')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="giftQuantity" class="col-1 col-form-label ">Số lượng</label>
                        <div class="col-5">
                            <input id="giftQuantity" type="text" class="form-control" wire:model.defer="giftQuantity"
                                onkeypress="return onlyNumberKey(event)">
                            @error('giftQuantity')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row justify-content-center btn-group-mt">
                        <div class="col-1">
                            @if ($isAdd)
                                <button wire:click.prevent="store" type="button" class="btn btn-primary"><i
                                        class="fa fa-plus"></i> Tạo mới</button>
                            @else
                                <button wire:click.prevent="store" type="button" class="btn btn-primary"><i
                                        class="fa fa-save"></i> Cập nhật</button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
