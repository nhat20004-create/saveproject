<x-mail::message>
# Xin chào {{ $comment->post->user->name }}!

**{{ $comment->user->name }}** vừa bình luận về bài viết
**{{ $comment->post->title }}** của bạn:

> {{ $comment->body }}

<x-mail::button :url="route('posts.show', $comment->post->slug)">
Xem bình luận
</x-mail::button>

Cảm ơn bạn,<br>
Đội ngũ Góc Nhìn 24h
</x-mail::message>
