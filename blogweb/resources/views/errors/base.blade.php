<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') - Góc Nhìn 24h</title>
    <style>
        body {
            margin: 0; min-height: 100vh; display: flex;
            align-items: center; justify-content: center;
            font-family: Arial, sans-serif; background: #f7f4f5;
            text-align: center; color: #333;
        }
        .code { font-size: 96px; font-weight: bold; color: #9f224e; }
        a {
            display: inline-block; margin-top: 16px; padding: 8px 20px;
            background: #9f224e; color: #fff; text-decoration: none;
            border-radius: 4px;
        }
        a:hover { background: #7a1a3b; }
    </style>
</head>
<body>
    <div>
        <div class="code">@yield('code')</div>
        <h2>@yield('heading')</h2>
        <p>@yield('message')</p>
        <a href="/blog">Về trang chủ</a>
    </div>
</body>
</html>
