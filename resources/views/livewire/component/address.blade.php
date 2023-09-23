<div>
    <div class="form-group row">
        <label for="Address" class="col-1 col-form-label">Địa chỉ</label>
        <div class="col-3">
            <input id="Address" wire:model="address" name='address' placeholder="Địa chỉ" type="text" class="form-control" {{$status?'disabled':''}}>
        </div>
                <label for="CustomerDistrict" class="col-1 col-form-label ">Thành phố/ Tỉnh</label>
        <div class="col-3" wire:ignore>
            <select wire:model="province_id" name='city' id="supplyProvince" class="custom-select select2-box" {{$status?'disabled':''}}>
                <option value="" hidden>Chọn Thành phố/ Tỉnh</option>
                @foreach($province as $key=> $item)
                    <option value="{{$key}}" {{$key==$province_id?'selected':''}}>{{$item}}</option>
                @endforeach
            </select>
        </div>
        <label for="CustomerProvince" class="col-1 col-form-label ">Quận/ Huyện </label>
        <div class="col-3" >
            <select wire:model="district_id" name='district' id="supplyDistrict" class="custom-select select2-box" {{$status?'disabled':''}}>
                <option value="" hidden>Chọn Quận/ Huyện</option>
                @foreach($district as $key=> $item)
                    <option value="{{$key}}" {{$key==$district_id?'selected':''}}>{{$item}}</option>
                @endforeach
            </select>
        </div>
        <!-- <div wire:loading class="loader"></div> -->
    </div>
    <div wire:loading class="loader"></div>
    <div class="form-group row">
        <label for="CustomerProvince" class="col-1 col-form-label ">Phường/ Xã </label>
        <div class="col-3" >
            <select wire:model="ward_id" id="supplyWard" name='ward' class="custom-select select2-box" {{$status?'disabled':''}}>
                <option value="" hidden>Chọn Phường/ Xã</option>
                @foreach($ward as $key=> $item)
                    <option value="{{$key}}" {{$key==$ward_id?'selected':''}}>{{$item}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
@section('js')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            $('#supplyProvince').on('change', function (e) {
                var data = $('#supplyProvince').select2("val");
            @this.set('province_id', data);
            });
            $('#supplyDistrict').on('change', function (e) {
                var data = $('#supplyDistrict').select2("val");
            @this.set('district_id', data);
            });
            $('#supplyWard').on('change', function (e) {
                var data = $('#supplyWard').select2("val");
            @this.set('ward_id', data);
            });
        })
    </script>

@endsection
