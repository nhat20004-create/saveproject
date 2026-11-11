@extends('Layout.index')

@section('title', 'Bài viết - Quản trị')

@section('content')
<div class="content-box">
    <h4 class="post-title mb-3">TẤT CẢ BÀI VIẾT</h4>
    @include('admin.nav')

    <table class="table table-sm">
        <thead>
            <tr>
                <th>Tiêu đề</th><th>Tác giả</th><th>Trạng thái</th><th></th>
            </tr>
        </thead>
        <tbody>
        @foreach($posts as $post)
            @php($url = route('admin.posts.destroy', $post->id))
            <tr>
                <td>{{ $post->title }}</td>
                <td>{{ $post->user->name ?? '' }}</td>
                <td>
                    {{ \App\Models\Post::STATUSES[$post->status] }}
                    @if($post->trashed())
                        <span class="badge badge-secondary">Thùng rác</span>
                    @endif
                </td>
                <td class="text-right">
                    <form method="post" class="d-inline" action="{{ $url }}"
                          onsubmit="return confirm('Xóa vĩnh viễn bài này?');">
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

    {{ $posts->links('pagination::bootstrap-4') }}
</div>
@endsection
