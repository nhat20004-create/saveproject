@extends('Layout.index')

@section('title', 'Đăng nhập - Góc Nhìn 24h')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="content-box">
            <h4 class="post-title mb-4">ĐĂNG NHẬP</h4>
            <form action="{{ route('login') }}" method="post">
                @csrf
                @include('auth.field', [
                    'name' => 'email', 'label' => 'Email',
                    'type' => 'email',
                ])
                @include('auth.field', [
                    'name' => 'password', 'label' => 'Mật khẩu',
                    'type' => 'password',
                ])
                <div class="form-group form-check">
                    <input type="checkbox" name="remember" id="remember"
                           class="form-check-input">
                    <label class="form-check-label" for="remember">
                        Ghi nhớ đăng nhập
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">
                    Đăng nhập
                </button>
            </form>
            <p class="mt-3 mb-1">
                <a href="{{ route('password.request') }}">Quên mật khẩu?</a>
            </p>
            <p class="mb-0">
                Chưa có tài khoản?
                <a href="{{ route('register') }}">Đăng ký</a>
            </p>
        </div>
    </div>
</div>
@endsection
