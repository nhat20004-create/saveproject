<header class="bg-white text-center py-4">
    <a href="{{ route('blog') }}" class="brand text-decoration-none">
        GÓC NHÌN 24H
    </a>

    <p class="text-muted mb-0 mt-2">
        Blog cộng đồng: tin tức trong ngày và chuyện đời sống
    </p>
</header>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <button class="navbar-toggler bg-light" type="button"
                data-toggle="collapse" data-target="#mainMenu">
            Menu
        </button>

        <div class="collapse navbar-collapse" id="mainMenu">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('blog') }}">Trang chủ</a>
                </li>
                @foreach($navCategories ?? [] as $item)
                    @php($url = route('blog', ['category' => $item->slug]))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $url }}">
                            {{ $item->name }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <ul class="navbar-nav ml-auto">
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('posts.create') }}">
                            Viết bài
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('posts.mine') }}">
                            Bài của tôi
                        </a>
                    </li>
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('admin.dashboard') }}">
                                Quản trị
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('password.change') }}">
                            Xin chào, {{ auth()->user()->name }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="post"
                              class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link"
                                    style="border:0;">Đăng xuất</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            Đăng nhập
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            Đăng ký
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
