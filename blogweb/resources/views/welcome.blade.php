{{-- Trang mặc định Laravel; trang chủ thật là /blog --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Góc Nhìn 24h</title>
    <meta http-equiv="refresh" content="0; url={{ url('/blog') }}">
</head>
<body>
    <p><a href="{{ url('/blog') }}">Vào blog Góc Nhìn 24h</a></p>
</body>
</html>
