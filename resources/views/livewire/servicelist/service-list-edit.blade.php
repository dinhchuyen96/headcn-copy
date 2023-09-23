<div>
    <div class="page-content fade-in-up">
        <div wire:loading class="loader"></div>
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thông tin danh mục dịch vụ khác</div>
            </div>
            <div class="ibox-body">
                <form>
                    @csrf
                    <div class="form-group row">
                        <label for="serviceName" class="col-1 col-form-label ">Tên dịch vụ<span
                                class="text-danger"> *</span></label>
                        <div class="col-5">
                            <input id="serviceName" placeholder="Tên dịch vụ" type="text" class="form-control"
                                wire:model.defer="serviceName">
                            @error('serviceName')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="serviceType" class="col-1 col-form-label ">Loại dịch vụ<span
                                class="text-danger"> *</span></label>
                        <div class="col-5">
                            <select wire:model="serviceType" name='serviceType' id="serviceType"
                                class="custom-select select2-box">
                                <option value="">Chọn loại DV</option>
                                <option value="1">DV khác (Thu)</option>
                                <option value="2">DV khác (Chi)</option>
                            </select>
                            @error('serviceType')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row justify-content-center btn-group-mt">
                        <div>
                            <a href="{{ route('servicelist.index') }}" class="btn btn-default">
                                <i class="fa fa-arrow-left"></i>
                                Trở lại
                            </a>
                            <button wire:click.prevent="store" type="button" class="btn btn-primary"><i
                                    class="fa fa-save"></i>Cập nhật</button>

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
            $('#serviceType').on('change', function(e) {
                var data = $('#serviceType').select2("val");
                @this.set('serviceType', data);
            });
        });
    </script>
@endsection
