<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan Digital')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <header class="header">
        <h1>Perpustakaan Digital</h1>
        <p>Jendela dunia ada di genggamanmu</p>
    </header>

    <nav class="navbar">
        <div class="nav-inner">
            <a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('buku.index') }}" class="{{ request()->routeIs('buku.*') ? 'active' : '' }}">Daftar Buku</a>
        </div>
    </nav>

    <main>
        <div class="container">
            @yield('content')
        </div>
    </main>

    <footer class="footer">
        © 2026 Perpustakaan Digital — 250441100029
    </footer>
</body>
</html>