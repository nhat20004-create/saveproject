<div class="row mb-4 pb-4 border-bottom">
    @if($post->imageUrl())
        <div class="col-md-5">
            <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}"
                 class="post-image">
        </div>
    @endif

    <div class="{{ $post->imageUrl() ? 'col-md-7' : 'col-12' }} mt-3 mt-md-0">
        <span class="category">{{ $post->category->name ?? 'Khác' }}</span>
        <h4 class="post-title mt-2">{{ $post->title }}</h4>
        <p class="text-muted small">
            {{ $post->published_at?->format('d/m/Y') }}
            @if($post->user)
                · {{ $post->user->name }}
            @endif
            · {{ $post->comments_count ?? 0 }} bình luận
        </p>

        @foreach($post->tags as $tag)
            <a class="tag-chip"
               href="{{ route('blog', ['tag' => $tag->slug]) }}">
                #{{ $tag->name }}
            </a>
        @endforeach

        <p>{{ Str::limit(strip_tags($post->content), 140) }}</p>
        <a href="{{ route('posts.show', $post->slug) }}"
           class="btn btn-outline-primary btn-sm">Đọc thêm</a>
    </div>
</div>
