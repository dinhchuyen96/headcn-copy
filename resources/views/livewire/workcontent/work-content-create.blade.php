<div>
    <div class="page-content fade-in-up">
        <div wire:loading class="loader"></div>
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thông tin nội dung công việc</div>
            </div>
            <div class="ibox-body">
                <form>
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-2 col-form-label ">Nội dung công việc<span
                                class="text-danger"> *</span></label>
                        <div class="col-10">
                            <textarea id="name" placeholder="Nội dung công việc" rows="4" class="form-control"
                                wire:model.defer="name">
                            </textarea>
                            @error('name')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="name" class="col-2 col-form-label">Loại công việc<span
                                class="text-danger">*</span></label>
                        <div class="col-4">
                            <select wire:model="type" name="type" id="type" class="custom-select select2-box">
                                @foreach ($typeList as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row justify-content-center btn-group-mt">
                        <div>
                            <a href="{{ route('work-content.index') }}" class="btn btn-default">
                                <i class="fa fa-arrow-left"></i>
                                Trở lại
                            </a>
                            <button wire:click.prevent="store" type="button" class="btn btn-primary"><i
                                    class="fa fa-plus"></i> Tạo mới</button>
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
            $('#type').on('change', function(e) {
                var data = $('#type').select2("val");
                @this.set('type', data);
            });
        });
    </script>
@endsection
