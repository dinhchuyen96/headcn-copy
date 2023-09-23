<div>
    <div class="page-content fade-in-up">
        <div wire:loading class="loader"></div>
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thông tin hóa đơn</div>
            </div>
            <div class="ibox-body">
                <form>
                    @csrf
                    <div class="form-group row">
                        <label for="serviceType" class="col-2 col-form-label ">Loại dịch vụ<span
                                class="text-danger"> *</span></label>
                        <div class="col-10">
                            <select wire:model="serviceType" name='serviceType' id="serviceType" class="custom-select">
                                <option value="">--Tất cả--</option>
                                @foreach ($listService as $value)
                                    <option value="{{ $value['id'] }}">
                                        {{ $value['title'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('content')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="supplier_id" class="col-2 col-form-label ">Nhà cung cấp<span
                                class="text-danger"> *</span></label>
                        <div class="col-10">
                            <select wire:model="supplier_id" name='supplier_id' id="supplier_id" class="custom-select">
                                <option value="">--Tất cả--</option>
                                @foreach ($suppliers as $value)
                                    <option value="{{ $value->id }}">
                                        {{ $value->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="content" class="col-2 col-form-label ">Nội dung<span
                                class="text-danger"> *</span></label>
                        <div class="col-10">
                            <textarea tabindex="2" class="form-control" id="content" name="content" rows="5"
                                wire:model.defer='content'></textarea>
                            @error('content')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="price" class="col-2 col-form-label ">Chi phí<span
                                class="text-danger"> *</span></label>
                        <div class="col-10">
                            <input id="price" type="text" class="form-control"
                                onkeypress="return onlyNumberKey(event)" wire:model.defer="price">
                            @error('price')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row justify-content-center btn-group-mt">
                        <div>
                            <a href="{{ route('chinoibo.index') }}" class="btn btn-default">
                                <i class="fa fa-arrow-left"></i>
                                Trở lại
                            </a>
                            <button wire:click.prevent="update" type="button" class="btn btn-primary"><i
                                    class="fa fa-pencil"></i> Cập nhập</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#supplier_id').on('change', function(e) {
            var data = $('#supplier_id').val();
            @this.set('supplier_id', data);
        });

        $('#serviceType').on('change', function(e) {
            var data = $('#serviceType').val();
            @this.set('serviceType', data);
        });
    });
</script>
