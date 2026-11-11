<form action="{{ route('posts.restore', $post->id) }}" method="post"
      class="d-inline">
    @csrf
    <button class="btn btn-link btn-sm p-0">Khôi phục</button>
</form>
|
<form action="{{ route('posts.force', $post->id) }}" method="post"
      class="d-inline" onsubmit="return confirm('Xóa vĩnh viễn bài này?');">
    @csrf
    @method('DELETE')
    <button class="btn btn-link btn-sm text-danger p-0">Xóa vĩnh viễn</button>
</form>
