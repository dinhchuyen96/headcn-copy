@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-5">
            <!-- PAGE CONTENT BEGINS -->
        {!! Form::open(array('route' => 'update','method'=>'POST')) !!}
        <!-- #section:elements.form -->
            {{ csrf_field() }}
            <div class="form-group">
                <label for="username" class="col-md-4 control-label">Tên đăng nhập</label>

                <div class="col-md-6">
                    <input id="name" type="text" class="form-control" name="name" value="{{ Auth::user()->name }}">
                </div>
            </div>

            <div class="form-group">
                <label for="username" class="col-md-4 control-label">Email</label>

                <div class="col-md-6">
                    <input id="email" type="email" class="form-control" name="email" value="{{ Auth::user()->email }}">
                </div>
            </div>

            <div class="form-group{{ $errors->has('current_password') ? ' has-error' : '' }}">
                <label for="current_password" class="col-md-4 control-label">Mật khẩu hiện tại<span class="text-danger"> *</span></label>

                <div class="col-md-6">
                    <input id="current_password" type="password" class="form-control" name="current_password" value="{{ old('current_password') }}" required>

                    @if (session('error'))
                        <span class="help-block">
                            <strong>{{ session('error') }}</strong>
                        </span>
                    @endif

                </div>
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="col-md-4 control-label">Mật khẩu mới<span class="text-danger"> *</span></label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control" name="password" required>

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('confirm_password') ? ' has-error' : '' }}">
                <label for="confirm_password" class="col-md-4 control-label">Nhập lại mật khẩu<span class="text-danger"> *</span></label>

                <div class="col-md-6">
                    <input id="confirm_password" type="password" class="form-control" name="confirm_password" required>

                    @if ($errors->has('confirm_password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('confirm_password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="clearfix form-actions">
                <div class="col-md-offset-3 col-md-9">
                    <button class="btn btn-info" type="submit">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        Thay đổi
                    </button>
                    &nbsp; &nbsp; &nbsp;
                    <button class="btn" type="reset">
                        <i class="ace-icon fa fa-undo bigger-110"></i>
                        Làm mới
                    </button>
                </div>
            </div>

            <div class="hr hr-24"></div>
            {!! Form::close() !!}
            {{--</form>--}}
       <!-- /.col -->
        </div>
    </div><!-- /.row -->

@endsection
