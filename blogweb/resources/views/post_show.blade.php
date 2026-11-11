@extends('Layout.index')

@section('title', $post->title . ' - Góc Nhìn 24h')

@section('content')
<div class="content-box">
    <span class="category">{{ $post->category->name ?? 'Khác' }}</span>
    <h2 class="post-title mt-2">{{ $post->title }}</h2>
    <p class="text-muted">
        {{ ($post->published_at ?? $post->created_at)?->format('d/m/Y H:i') }}
        @if($post->user)
            · {{ $post->user->name }}
        @endif
        @unless($post->isPublished())
            · <span class="badge badge-main">
                {{ \App\Models\Post::STATUSES[$post->status] }}
            </span>
        @endunless
    </p>

    @foreach($post->tags as $tag)
        <a class="tag-chip" href="{{ route('blog', ['tag' => $tag->slug]) }}">
            #{{ $tag->name }}
        </a>
    @endforeach

    @if($post->imageUrl())
        <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}"
             class="post-image-lg my-3">
    @endif

    <div style="white-space: pre-line;">{{ $post->content }}</div>

    @can('update', $post)
        <hr>
        <a href="{{ route('posts.edit', $post->id) }}"
           class="btn btn-primary btn-sm">Sửa bài viết</a>
        <form action="{{ route('posts.destroy', $post->id) }}" method="post"
              class="d-inline"
              onsubmit="return confirm('Chuyển bài viết vào thùng rác?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
        </form>
    @endcan

    <div class="mt-3">
        <a href="{{ route('blog') }}">← Quay lại trang chủ</a>
    </div>
</div>

@if($post->isPublished())
    @include('partials.comments')
@endif
@endsection
