<div>
    <div class="page-heading">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fa fa-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Thông tin trả lại hàng bán</li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div wire:loading class="loader"></div>
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thông tin trả lại hàng bán</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="giftNameSearch" class="col-2 col-form-label ">Tên quà tặng</label>
                        <div class="col-4">
                            <input type="text" name="giftNameSearch" class="form-control size13"
                                wire:model.debounce.1000ms="giftNameSearch" id='giftNameSearch' autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row justify-content-center">
                        @include('layouts.partials.button._reset')
                    </div>
                </form>

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
                            <button wire:click.prevent="store" type="button" class="btn btn-primary"><i
                                    class="fa fa-plus"></i> Tạo mới</button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
