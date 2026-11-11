@extends('Layout.index')

@section('title', 'Đổi mật khẩu - Góc Nhìn 24h')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="content-box">
            <h4 class="post-title mb-4">ĐỔI MẬT KHẨU</h4>
            <form action="{{ route('password.change.update') }}" method="post">
                @csrf
                @method('PUT')
                @include('auth.field', [
                    'name' => 'current_password',
                    'label' => 'Mật khẩu hiện tại',
                    'type' => 'password',
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
                    Đổi mật khẩu
                </button>
                <a href="{{ route('blog') }}" class="btn btn-light">Hủy</a>
            </form>
        </div>
    </div>
</div>
@endsection
