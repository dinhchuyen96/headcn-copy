<div>
    <div class="form-group row">
        <label for="name" class="col-1 col-form-label ">Tên vai trò (<span style='color:red;'>*</span>)</label>
        <div class="col-3">
            {!! Form::text('name', null, array('placeholder' => 'Tên vai trò','class' => 'form-control')) !!}
            @error('name')
                @include('layouts.partials.text._error')
            @enderror
        </div>
        <label for="name" class="col-1 col-form-label ">Tên vai trò <span style='color:red;'> *</span></label>
        <div class="col-3">
            {!! Form::text('name', null, array('placeholder' => 'Tên vai trò','class' => 'form-control')) !!}
            @error('name')
                @include('layouts.partials.text._error')
            @enderror
        </div>
        <label for="percentage" class="col-1 col-form-label ">Phần trăm hoa hồng (<span style='color:red;'>*</span>)</label>
        <div class="col-3">
            {!! Form::number('percentage', null, array('placeholder' => 'Phần trăm hoa hồng','class' => 'form-control')) !!}
            @error('percentage')
            @include('layouts.partials.text._error')
            @enderror
        </div>
    </div>
    {{-- <div class="form-group row" @if(checkRoute('edit')) hidden @endif>
        <label for="permissions" class="col-1 col-form-label ">Quyền (<span style='color:red;'>*</span>)</label>
        <div class="col-5">
            {!! Form::select('permissions[]', $rolePermissions,['name'], array('class' => 'form_control select_box col-md-12','multiple')) !!}
            @error('permissions')
                @include('layouts.partials.text._error')
            @enderror
        </div>
    </div> --}}
    <div class="form-group row">
        <label for="permissions" class="col-1 col-form-label ">Quyền <span style='color:red;'> *</span></label>
        <div class="col-3">
            <select name="permissions[]" id="permissions" autocomplete="off" class="form_control select_box col-md-12" multiple>
                @foreach($rolePermissions as $key => $rolePermission)
                    <option value='{{$key}}' @foreach($permissions as $value)@if($key == $value) selected @endif @endforeach>{{$rolePermission}}</option>
                @endforeach
            </select>
            @error('permissions')
                @include('layouts.partials.text._error')
            @enderror
        </div>
    </div>

    {{-- <div class="form-group row justify-content-center">
        <div class="col-1">
            <button name="submit" type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> {{isset($data)?"Chỉnh sửa":"Tạo
                mới"}}</button>
        </div>
    </div> --}}
    <div class="form-group row justify-content-center">
        @include('layouts.partials.button._back')
    <div class="col-1">
        {{-- @if(checkRoute('create')) --}}
            @include('layouts.partials.button._save')
        {{-- @endif
        @if(checkRoute('edit'))
            @include('layouts.partials.button._save')
        @endif --}}
    </div>
</div>

