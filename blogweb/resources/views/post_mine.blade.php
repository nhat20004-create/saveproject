@extends('Layout.index')

@section('title', 'Bài của tôi - Góc Nhìn 24h')

@section('content')
<div class="content-box">
    <h4 class="post-title mb-3">
        {{ $trashed ? 'THÙNG RÁC' : 'BÀI CỦA TÔI' }}
    </h4>

    <p>
        <a href="{{ route('posts.mine') }}">Bài viết</a> |
        <a href="{{ route('posts.trash') }}">Thùng rác</a> |
        <a href="{{ route('posts.create') }}">+ Viết bài mới</a>
    </p>

    <table class="table table-sm">
        <thead>
            <tr>
                <th>Tiêu đề</th><th>Danh mục</th><th>Trạng thái</th><th></th>
            </tr>
        </thead>
        <tbody>
        @forelse($posts as $post)
            <tr>
                <td>{{ $post->title }}</td>
                <td>{{ $post->category->name ?? '' }}</td>
                <td>{{ \App\Models\Post::STATUSES[$post->status] }}</td>
                <td class="text-right">
                    @if($trashed)
                        @include('partials.trash-actions')
                    @else
                        <a href="{{ route('posts.show', $post->slug) }}">
                            Xem</a> |
                        <a href="{{ route('posts.edit', $post->id) }}">Sửa</a>
                        <form action="{{ route('posts.destroy', $post->id) }}"
                              method="post" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-link btn-sm text-danger p-0">
                                Xóa
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-muted">Chưa có bài viết.</td></tr>
        @endforelse
        </tbody>
    </table>

    {{ $posts->links('pagination::bootstrap-4') }}
</div>
@endsection
