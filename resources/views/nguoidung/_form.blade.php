@php
use App\Enum\EUserPosition;
@endphp

<div>
    <div class="form-group row">
        <label for="name" class="col-1 col-form-label ">Tên người dùng <span style='color:red;'> *</span></label>
        <div class="col-3">
            {!! Form::text('name', null, ['placeholder' => 'Tên người dùng', 'class' => 'form-control']) !!}
            @error('name')
                @include('layouts.partials.text._error')
            @enderror
        </div>
        <label for="username" class="col-1 col-form-label">Tài khoản <span style='color:red;'> *</span></label>
        <div class="col-3">
            {!! Form::text('username', null, ['placeholder' => 'username', 'class' => 'form-control', isset($data) ? 'disabled' : '']) !!}
            @error('username')
                @include('layouts.partials.text._error')
            @enderror
        </div>
        <label for="Email" class="col-1 col-form-label">Email <span style='color:red;'> *</span></label>
        <div class="col-3">
            {!! Form::text('email', null, ['placeholder' => 'Email', 'class' => 'form-control', isset($data) ? 'disabled' : '']) !!}
            @error('email')
                @include('layouts.partials.text._error')
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <label for="postitions" class="col-1 col-form-label ">Chức vụ</label>
        <div class="col-3">
            {!! Form::select('positions', EUserPosition::toEnumArray(), isset($data) ? $data->positions : null, ['class' => 'form_control col-md-12 select_box']) !!}
        </div>
        @if (!isset($data))
            <label for="password" class="col-1 col-form-label ">Mật khẩu <span style='color:red;'> *</span></label>
            <div class="col-3">
                <input id="password" name="password" type="password" placeholder="Mật khẩu" class="form-control"
                    value="">
                @error('password')
                    @include('layouts.partials.text._error')
                @enderror
            </div>
        @else
            <label for="password" class="col-1 col-form-label ">Mật khẩu mới</label>
            <div class="col-3">
                {!! Form::text('password_new', null, ['placeholder' => 'Mật khẩu mới', 'class' => 'form-control']) !!}
                @error('password_new')
                    @include('layouts.partials.text._error')
                @enderror
            </div>
        @endif
        @if (!isset($data))
            <label for="password_again" class="col-1 col-form-label ">Xác nhận mật khẩu <span
                    style='color:red;'> *</span></label>
            <div class="col-3">
                <input id="password_confirm" name="password_confirm" type="password" placeholder="Xác nhận mật khẩu"
                    class="form-control" value="">
                @error('password_confirm')
                    @include('layouts.partials.text._error')
                @enderror
            </div>
        @endif
    </div>
    {{-- <div class="form-group row">
        <label for="postitions" class="col-1 col-form-label ">Chức vụ</label>
        <div class="col-3">
            {!! Form::select('positions',[1=>'Giám đốc',2=>'Nhân viên bán hàng',3=>'Nhân viên kỹ thuật',4=>'Nhân viên kiểm tra',5=>'Kế toán',6=>'Kiểm kho',7=>'Thủ quỹ',8=>'Khác'],isset($data)?$data->positions:null, array('class' => 'form_control col-md-12 select_box')) !!}
        </div>
    </div> --}}

    <div class="form-group row">
        <label for="roles" class="col-1 col-form-label ">Quyền <span style='color:red;'> *</span></label>
        <div class="col-3">
            {!! Form::select('roles[]', $roles, isset($rolesUser) ? $rolesUser : [], ['class' => 'form_control select_box col-md-12', 'multiple']) !!}
            @error('roles')
                @include('layouts.partials.text._error')
            @enderror
        </div>
    </div>
    <div class="form-group row justify-content-center btn-group-mt">
        <div>
            <a href="{{ route('nguoiDung.index') }}" class="btn btn-default">
                <i class="fa fa-arrow-left"></i>
                Trở lại
            </a>
            <button name="submit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                {{ isset($data) ? 'Cập nhật' : "Tạo mới" }}
            </button>
        </div>
    </div>
</div>
