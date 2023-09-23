<div>
    <div class="page-heading">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fa fa-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Import MTOC</li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div wire:loading class="loader"></div>
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Import MTOC</div>
            </div>
            <div class="ibox-body">
                <form>
                    @csrf
                    <div class="form-group row">
                        <label for="import_file" class="col-1 col-form-label ">Chọn file<span class="text-danger"> *</span></label>
                        <div class="col-5">
                            <input id="import_file" type="file" class="form-control" 
                                wire:model.defer="import_file">
                                @error('import_file') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                        <label for="import_file" class="col-6 col-form-label ">Tải file mẫu <a href="">tại đây</a></label>
                    </div>
                    <div class="form-group row justify-content-center">
                        <div class="col-1">
                            <button wire:click.prevent="store" type="button" class="btn btn-primary"><i
                                    class="fa fa-upload"></i> Import</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded',function(){
            $('#OptionModel').on('change', function (e) {
                var data = $('#OptionModel').select2("val");
                @this.set('car_mode_list_id', data);
            });
            $('#ModelName').on('change', function (e) {
                var data = $('#ModelName').select2("val");
                @this.set('car_mode_name_id', data);
            });
            $('#Type').on('change', function (e) {
                var data = $('#Type').select2("val");
                @this.set('car_mode_type_id', data);
            });
            $('#ColorCode').on('change', function (e) {
                var data = $('#ColorCode').select2("val");
                @this.set('car_color_code_id', data);
            });
        });
    </script>
</div>
