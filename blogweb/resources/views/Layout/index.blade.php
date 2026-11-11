<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Góc Nhìn 24h')</title>
    @php($cdn = 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist')
    <link rel="stylesheet" href="{{ $cdn }}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    @yield('styles')
</head>
<body>
    @include('partials.navbar')

    <main class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    @include('partials.footer')

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="{{ $cdn }}/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
