@extends('layouts.auth')

@section('title', 'Đăng nhập')

@section('css')
    <link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">
    <link href="{{ asset('assets/css/auth-light.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="brand">
        <span  >{{ env('APP_HEADCODE') }}</span>
    </div>
    <form id="login-form" action="{{ route('user.doLogin') }}" method="post">
        @csrf
        <h2 class="login-title">ETS</h2>
        @if (Session::get('error'))
            <div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert">x</a>
                <strong>{{ Session::get('error') }}</strong>
            </div>
        @endif
        <div class="form-group">
            <div class="input-group-icon right">
                <div class="input-icon"></div>
                <input class="form-control form-login" type="text" value="{{ old('email') }}" id="email" name="email"
                    placeholder="Mã đăng nhập" autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <div class="input-group-icon right">
                <div class="input-icon"><i class="fa fa-lock font-16"></i></div>
                <input class="form-control form-login" type="password" id="password" name="password" placeholder="Mật khẩu">
            </div>
        </div>
        <!-- <div class="form-group d-flex justify-content-between">
            <label class="ui-checkbox ui-checkbox-info">
                <input type="checkbox">
                <span class="input-span"></span>Nhớ mật khẩu</label>
            <a href="#">Quên mật khẩu?</a>
        </div> -->
        <div class="form-group">
            <button class="btn btn-info btn-block btn-login" type="submit">Đăng nhập</button>
        </div>
    </form>
@endsection

@section('js')
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(function() {
            if ($('#login-form').length) {
                $('#login-form').validate({
                    errorClass: "help-block",
                    rules: {
                        email: {
                            required: true
                        },
                        password: {
                            required: true,
                            minlength: 6
                        }
                    },
                    messages: {
                        email: {
                            required: "Bạn chưa nhập username"
                        },
                        password: {
                            required: "Bạn chưa nhập mật khẩu",
                            minlength: "Mật khâủ phải chứa ít nhất 6 kí tự"
                        }
                    },
                    highlight: function(e) {
                        $(e).closest(".form-group").addClass("has-error")
                    },
                    unhighlight: function(e) {
                        $(e).closest(".form-group").removeClass("has-error")
                    },
                });
            }
        });
    </script>
@endsection
