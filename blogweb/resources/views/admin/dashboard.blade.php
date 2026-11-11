@extends('Layout.index')

@section('title', 'Thống kê - Quản trị')

@section('content')
<div class="content-box">
    <h4 class="post-title mb-3">QUẢN TRỊ</h4>
    @include('admin.nav')

    @php($stats = ['Thành viên' => $users, 'Bài đã đăng' => $posts,
                   'Bình luận' => $comments])
    <div class="row">
        @foreach($stats as $label => $value)
            <div class="col-md-4 mb-3">
                <div class="content-box stat-box mb-0">
                    <div class="text-muted">{{ $label }}</div>
                    <div class="num">{{ $value }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <h5 class="post-title mt-3">5 BÀI NHIỀU BÌNH LUẬN NHẤT</h5>
    <table class="table table-sm">
        <thead><tr><th>Tiêu đề</th><th>Bình luận</th></tr></thead>
        <tbody>
        @forelse($top as $post)
            <tr>
                <td>
                    <a href="{{ route('posts.show', $post->slug) }}">
                        {{ $post->title }}
                    </a>
                </td>
                <td>{{ $post->comments_count }}</td>
            </tr>
        @empty
            <tr><td colspan="2" class="text-muted">Chưa có dữ liệu.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
