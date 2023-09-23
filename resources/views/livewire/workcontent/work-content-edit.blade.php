<div>
    <div class="page-content fade-in-up">
        <div wire:loading class="loader"></div>
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Sửa nội dung công việc</div>
            </div>
            <div class="ibox-body">
                <form>
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-2 col-form-label ">Nội dung công việc<span
                                class="text-danger">*</span></label>
                        <div class="col-4">
                            <textarea id="name" placeholder="Nội dung công việc" rows="4" class="form-control" wire:model.defer="name">{{ $name }}
                            </textarea>
                            @error('name')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="name" class="col-2 col-form-label">Loại công việc<span
                                class="text-danger">*</span></label>
                        <div class="col-4">
                            <select disabled wire:model="type" name="type" id="type" class="custom-select select2-box">
                                <option value="0">Công việc trong</option>
                                <option value="1">Công việc ngoài</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row justify-content-center">
                        <div class="col-1">
                            <button wire:click.prevent="update()" type="button" class="btn btn-primary"><i
                                    class="fa fa-save"></i> Cập nhập</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
