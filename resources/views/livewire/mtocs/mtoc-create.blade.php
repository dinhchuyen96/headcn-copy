<div>
    <div class="page-content fade-in-up">
        <div wire:loading class="loader"></div>
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thông tin MTOC</div>
            </div>
            <div class="ibox-body">
                <form>
                    @csrf
                    <div class="form-group row">
                        <label for="MTOCCode" class="col-1 col-form-label ">Mã MTOC<span
                                class="text-danger"> *</span></label>
                        <div class="col-3">
                            <input id="MTOCCode" type="text" readonly class="form-control" wire:model="mtocd">
                            @error('mtocd') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                        <label for="colorCode" class="col-1 col-form-label ">Mã màu xe<span
                                class="text-danger"> *</span></label>
                        <div class="col-3">
                            <input id="colorCode" type="text" class="form-control" wire:model="colorCode">
                            @error('colorCode') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                        <label for="colorName" class="col-1 col-form-label ">Màu xe<span
                                class="text-danger"> *</span></label>
                        <div class="col-3">
                            <input id="colorName" type="text" class="form-control" wire:model="colorName">
                            @error('colorCode') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="modelCode" class="col-1 col-form-label ">Tên đời xe<span
                                class="text-danger"> *</span></label>
                        <div class="col-3">
                            <input id="modelCode" type="text" class="form-control" wire:model="modelCode">
                            @error('modelCode') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                        <label for="modelType" class="col-1 col-form-label ">Phân loại<span
                                class="text-danger"> *</span></label>
                        <div class="col-3">
                            <input id="modelType" type="text" class="form-control" wire:model="modelType">
                            @error('modelType') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>

                        <label for="optionCode" class="col-1 col-form-label ">Danh mục</label></label>
                        <div class="col-3">
                            <input id="optionCode" type="text" class="form-control" placeholder=""
                                wire:model="optionCode">
                            @error('optionCode') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="suggest_price" class="col-1 col-form-label ">Giá đề xuất<span
                            class="text-danger"> *</span></label></label>
                        <div class="col-3">
                            <input id="suggest_price" type="number" onkeypress="return onlyNumberKey(event)" class="form-control" placeholder=""
                                wire:model.lazy="suggest_price">
                            @error('suggest_price') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-group row justify-content-center btn-group-mt">
                        <div>
                            <a href="{{ route('mtoc.index') }}" class="btn btn-default">
                                <i class="fa fa-arrow-left"></i>
                                Trở lại
                            </a>
                            @if($this->mtoclist)
                                <button wire:click.prevent="store" type="button" class="btn btn-primary"><i
                                        class="fa fa-save"></i> Cập nhật</button>
                            @else
                                <button wire:click.prevent="store" type="button" class="btn btn-primary"><i
                                        class="fa fa-plus"></i> Tạo mới</button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
