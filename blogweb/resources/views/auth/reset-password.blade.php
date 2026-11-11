@extends('Layout.index')

@section('title', 'Đặt lại mật khẩu - Góc Nhìn 24h')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="content-box">
            <h4 class="post-title mb-4">ĐẶT LẠI MẬT KHẨU</h4>
            <form action="{{ route('password.update') }}" method="post">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                @include('auth.field', [
                    'name' => 'email', 'label' => 'Email',
                    'type' => 'email',
                    'value' => $email,
                ])
                @include('auth.field', [
                    'name' => 'password', 'label' => 'Mật khẩu mới',
                    'type' => 'password',
                ])
                @include('auth.field', [
                    'name' => 'password_confirmation',
                    'label' => 'Xác nhận mật khẩu mới',
                    'type' => 'password',
                ])
                <button type="submit" class="btn btn-primary">
                    Đặt lại mật khẩu
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
