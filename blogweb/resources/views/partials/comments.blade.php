<div class="content-box">
    <h5 class="post-title mb-3">BÌNH LUẬN ({{ $post->comments->count() }})</h5>

    @auth
        <form action="{{ route('comments.store', $post->slug) }}" method="post"
              class="mb-4">
            @csrf
            <div class="form-group">
                <textarea name="body" rows="3" class="form-control" required
                    placeholder="Viết bình luận...">{{ old('body') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">
                Gửi bình luận
            </button>
        </form>
    @else
        <p>
            <a href="{{ route('login') }}">Đăng nhập</a>
            để bình luận.
        </p>
    @endauth

    @forelse($post->comments as $comment)
        <div class="border-bottom py-2">
            <strong>{{ $comment->user->name ?? 'Ẩn danh' }}</strong>
            <small class="text-muted">
                · {{ $comment->created_at->format('d/m/Y H:i') }}
            </small>
            <p class="mb-1" style="white-space: pre-line;">
                {{ $comment->body }}
            </p>

            @can('delete', $comment)
                <form action="{{ route('comments.destroy', $comment->id) }}"
                      method="post" class="d-inline"
                      onsubmit="return confirm('Xóa bình luận này?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn btn-link btn-sm text-danger p-0">
                        Xóa
                    </button>
                </form>
            @endcan
        </div>
    @empty
        <p class="text-muted mb-0">Chưa có bình luận nào.</p>
    @endforelse
</div>
