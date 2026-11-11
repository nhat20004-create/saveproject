@extends('Layout.index')

@section('title', 'Trang chủ - Góc Nhìn 24h')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="content-box">
            <form action="{{ route('blog') }}" method="get" class="mb-4">
                <div class="input-group">
                    <input type="text" name="q" class="form-control"
                           placeholder="Tìm bài viết theo từ khóa..."
                           value="{{ $filters['q'] }}">
                    @if($filters['category'])
                        <input type="hidden" name="category"
                               value="{{ $filters['category'] }}">
                    @endif
                    @if($filters['tag'])
                        <input type="hidden" name="tag"
                               value="{{ $filters['tag'] }}">
                    @endif
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            Tìm
                        </button>
                    </div>
                </div>
            </form>

            <h4 class="mb-4">
                @if($filters['q'] !== '')
                    KẾT QUẢ CHO: "{{ $filters['q'] }}"
                @elseif($filters['category'] || $filters['tag'])
                    BÀI VIẾT ĐÃ LỌC
                    <a href="{{ route('blog') }}" class="small">(bỏ lọc)</a>
                @else
                    BÀI VIẾT MỚI NHẤT
                @endif
            </h4>

            @forelse($posts as $post)
                @include('partials.post-item')
            @empty
                <p>Không tìm thấy bài viết nào phù hợp.</p>
                @auth
                    <a href="{{ route('posts.create') }}"
                       class="btn btn-primary btn-sm">Viết bài mới</a>
                @endauth
            @endforelse

            <div class="d-flex justify-content-center">
                {{ $posts->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="content-box">
            <h5 class="post-title">GIỚI THIỆU</h5>
            <p>
                Góc Nhìn 24h là blog cộng đồng nhiều tác giả: đọc, đăng
                bài và bình luận về công nghệ, học tập, đời sống.
            </p>
        </div>
        <div class="content-box">
            <h5 class="post-title">DANH MỤC</h5>
            <ul class="list-group list-group-flush">
                @foreach($categories as $item)
                    @php($url = route('blog', ['category' => $item->slug]))
                    <li class="list-group-item d-flex justify-content-between">
                        <a href="{{ $url }}">{{ $item->name }}</a>
                        <span class="badge badge-main">
                            {{ $item->posts_count }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="content-box">
            <h5 class="post-title">TAG</h5>
            @forelse($tags as $tag)
                <a class="tag-chip"
                   href="{{ route('blog', ['tag' => $tag->slug]) }}">
                    #{{ $tag->name }}
                </a>
            @empty
                <p class="text-muted mb-0">Chưa có tag.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
