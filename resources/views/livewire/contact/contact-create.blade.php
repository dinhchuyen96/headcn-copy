<div>
    <div class="page-heading">
        <ol class="breadcrumb">
            <li class="fa fa-phone font-20" >
                <a href="{{ route('dashboard') }}"></a>
            </li>
            <li class="fa fa-phone font-20">Thông Tin Liên Hệ </li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div wire:loading class="loader"></div>
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thông Tin Cần Lưu</div>
            </div>
            <div class="ibox-body">
                <form>
                    @csrf
                    <div class="form-group row">
                        <label for="method_name" class="col-2 col-form-label ">Nội dung<span
                                class="text-danger"></span></label>
                        <div class="col-10">
                            <textarea id="method_name" placeholder="" rows="4" class="form-control"
                                      wire:model.defer="method_name">
                            </textarea>
                            @error('method_name')
                            @include('layouts.partials.text._error')
                            @enderror
                        </div>

                    </div>
                    <div class="form-group row justify-content-center">
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
