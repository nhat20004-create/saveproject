@extends('Layout.index')

@section('title', 'Viết bài - Góc Nhìn 24h')

@section('content')
<div class="content-box">
    <h4 class="post-title mb-4">TẠO BÀI VIẾT MỚI</h4>

    <form action="{{ route('posts.store') }}" method="post"
          enctype="multipart/form-data">
        @csrf
        @include('posts_form')

        <button type="submit" class="btn btn-primary">Lưu bài viết</button>
        <a href="{{ route('blog') }}" class="btn btn-light">Hủy</a>
    </form>
</div>
@endsection
