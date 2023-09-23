<div>
    <div class="page-heading">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fa fa-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Thông tin công ty trả góp</li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div wire:loading class="loader"></div>
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thông tin công ty trả góp</div>
            </div>
            <div class="ibox-body">
                <form>
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-2 col-form-label ">Tên công ty<span
                                class="text-danger"> (*)</span></label>
                        <div class="col-4">
                            <input id="name" placeholder="Tên công ty"  class="form-control"
                                   wire:model.defer="company_name" value="{{ $company_name }}">
                            </input>
                            @error('name')
                            @include('layouts.partials.text._error')
                            @enderror
                        </div>

                        <label for="address" class="col-2 col-form-label ">Địa chỉ công ty<span
                                class="text-danger"> (*)</span></label>
                        <div class="col-4">
                            <input id="address" placeholder="Địa chỉ công ty" class="form-control"
                                   wire:model.defer="company_address" value="{{ $company_address }}">
                            </input>
                            @error('address')
                            @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="benefit_percentage" class="col-2 col-form-label ">Phần trăm hoa hông (%)<span
                                class="text-danger"> (*)</span></label>
                        <div class="col-4">
                            <input type="number" id="benefit_percentage" placeholder="Phần trăm hoa hông (%)"  class="form-control" min="1" max="99"
                                   wire:model.defer="benefit_percentage" value="{{ $benefit_percentage }}">
                            </input>
                            @error('rose')
                            @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row justify-content-center">
                        <div class="col-1">
                            <button wire:click.prevent="update()" type="button" class="btn btn-primary"><i
                                    class="fa fa-plus"></i> Cập nhập mới</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
