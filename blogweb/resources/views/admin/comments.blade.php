@extends('Layout.index')

@section('title', 'Bình luận - Quản trị')

@section('content')
<div class="content-box">
    <h4 class="post-title mb-3">TẤT CẢ BÌNH LUẬN</h4>
    @include('admin.nav')

    <table class="table table-sm">
        <thead>
            <tr>
                <th>Người viết</th><th>Nội dung</th><th>Bài viết</th><th></th>
            </tr>
        </thead>
        <tbody>
        @foreach($comments as $comment)
            @php($url = route('comments.destroy', $comment->id))
            <tr>
                <td>{{ $comment->user->name ?? '' }}</td>
                <td>{{ Str::limit($comment->body, 80) }}</td>
                <td>{{ Str::limit($comment->post->title ?? '', 30) }}</td>
                <td class="text-right">
                    <form method="post" class="d-inline" action="{{ $url }}"
                          onsubmit="return confirm('Xóa bình luận này?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">
                            Xóa
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $comments->links('pagination::bootstrap-4') }}
</div>
@endsection
