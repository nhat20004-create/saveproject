<x-mail::message>
# Xin chào {{ $user->name }}!

Cảm ơn bạn đã đăng ký tài khoản tại **Góc Nhìn 24h**. Từ bây giờ bạn có
thể đăng bài viết kèm ảnh, bình luận và quản lý bài viết của mình.

<x-mail::button :url="route('blog')">
Truy cập Góc Nhìn 24h
</x-mail::button>

Chúc bạn có những trải nghiệm thú vị,<br>
Đội ngũ Góc Nhìn 24h
</x-mail::message>
