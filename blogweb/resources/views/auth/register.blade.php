@extends('Layout.index')

@section('title', 'Đăng ký - Góc Nhìn 24h')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="content-box">
            <h4 class="post-title mb-4">ĐĂNG KÝ TÀI KHOẢN</h4>
            <form action="{{ route('register') }}" method="post">
                @csrf
                @include('auth.field', [
                    'name' => 'name', 'label' => 'Họ tên',
                    'type' => 'text',
                ])
                @include('auth.field', [
                    'name' => 'email', 'label' => 'Email',
                    'type' => 'email',
                ])
                @include('auth.field', [
                    'name' => 'password', 'label' => 'Mật khẩu',
                    'type' => 'password',
                ])
                @include('auth.field', [
                    'name' => 'password_confirmation',
                    'label' => 'Xác nhận mật khẩu',
                    'type' => 'password',
                ])
                <button type="submit" class="btn btn-primary">
                    Đăng ký
                </button>
            </form>
            <p class="mt-3 mb-0">
                Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a>
            </p>
        </div>
    </div>
</div>
@endsection
