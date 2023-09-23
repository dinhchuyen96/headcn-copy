<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thông tin nhà cung cấp</div>
            </div>
            <div class="ibox-body">
                    <div class="form-group row">
                        <label for="SupplyCode" class="col-1 col-form-label ">Mã NCC <span class="text-danger"> *</span></label>
                        <div class="col-3">
                            <input id="SupplyCode" wire:model.defer="supplyCode" placeholder="Mã nhà cung cấp" type="text"
                                   class="form-control">
                            @error('supplyCode')<span class="text-danger">{{$message}}</span> @enderror
                        </div>
                        <label for="SupplyName" class="col-1 col-form-label ">Tên NCC <span class="text-danger"> *</span></label>
                        <div class="col-3">
                            <input id="SupplyName" wire:model.defer="supplyName" placeholder="Tên nhà cung cấp" type="text"
                                   class="form-control" >
                            @error('supplyName')<span class="text-danger">{{$message}}</span>@enderror
                        </div>
                        <label for="PhoneNumber" class="col-1 col-form-label ">Số điện thoại<span class="text-danger"> *</span></label>
                        <div class="col-3">
                            <input id="PhoneNumber" wire:model.defer="phoneNumber" type="number" placeholder="Số điện thoại"
                                   class="form-control" >
                            @error('phoneNumber')<span class="text-danger">{{$message}}</span>@enderror
                        </div>

                    </div>


                    <div class="form-group row">
                        
                        <label for="Email" class="col-1 col-form-label ">Email</label>
                        <div class="col-3">
                            <input id="Email" wire:model.defer="email" type="text" placeholder="Email" class="form-control" >
                        </div>
                        <label for="Address" class="col-1 col-form-label">Địa chỉ</label>
                        <div class="col-3">
                            <input id="Address" wire:model.defer="address" placeholder="Địa chỉ" type="text" class="form-control">
                        </div>
                        <label for="SupplyProvince" class="col-1 col-form-label ">Thành phố/ Tỉnh </label>
                        <div class="col-3">
                            <select wire:model="supplyProvince" id="supplyProvince" class="custom-select select2-box">
                                <option hidden>Chọn Thành phố/ Tỉnh</option>
                                @foreach($province as $key=> $item)
                                    <option value="{{$key}}">{{$item}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="SupplyDistrict" class="col-1 col-form-label ">Quận/Huyện</label>
                        <div class="col-3">
                            <select wire:model="supplyDistrict" id="supplyDistrict" class="custom-select select2-box">
                                <option hidden>Chọn Quận/ Huyện</option>
                                @foreach($district as $key=> $item)
                                    <option value="{{$key}}">{{$item}}</option>
                                @endforeach

                            </select>
                        </div>
                        <label for="SupplyWard" class="col-1 col-form-label ">Phường/ Xã</label>
                        <div class="col-3">
                            <select wire:model="supplyWard" id="supplyWard" class="custom-select select2-box">
                                <option hidden>Chọn Phường/ Xã</option>
                                @foreach($ward as $key=> $item)
                                    <option value="{{$key}}">{{$item}}</option>
                                @endforeach
                            </select>
                        </div>
                        <label for="SupplyUrl" class="col-1 col-form-label ">Trang chủ</label>
                        <div class="col-3">
                            <input id="SupplyUrl" wire:model.defer="supplyUrl" type="text" placeholder="Trang chủ"
                                   class="form-control" >
                        </div>
                        <div class="col-3">
                        </div>
                    </div>
                    <div class="form-group row justify-content-center btn-group-mt">
                        <div>
                            <a href="{{ route('nhacungcap.dsnhacungcap.index') }}" class="btn btn-default">
                                <i class="fa fa-arrow-left"></i>
                                Trở lại
                            </a>
                            <button type="button" class="btn btn-primary" wire:click.prevent="store()">
                                <i class="fa fa-plus"></i>
                                Tạo mới
                            </button>
                        </div>
                    </div>

            </div>
        </div>
    </div>
</div>
@section('js')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            $('#supplyProvince').on('change', function (e) {
                var data = $('#supplyProvince').select2("val");
            @this.set('supplyProvince', data);
            });
            $('#supplyDistrict').on('change', function (e) {
                var data = $('#supplyDistrict').select2("val");
            @this.set('supplyDistrict', data);
            });
            $('#supplyWard').on('change', function (e) {
                var data = $('#supplyWard').select2("val");
            @this.set('supplyWard', data);
            });
        })
    </script>

@endsection
