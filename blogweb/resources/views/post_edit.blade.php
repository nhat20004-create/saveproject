@extends('Layout.index')

@section('title', 'Sửa bài viết - Góc Nhìn 24h')

@section('content')
<div class="content-box">
    <h4 class="post-title mb-4">SỬA BÀI VIẾT</h4>

    <form action="{{ route('posts.update', $post->id) }}" method="post"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('posts_form')

        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        <a href="{{ route('posts.mine') }}" class="btn btn-light">Hủy</a>
    </form>
</div>
@endsection
