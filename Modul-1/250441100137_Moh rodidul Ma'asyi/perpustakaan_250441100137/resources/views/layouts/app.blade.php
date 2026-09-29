<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Beranda') | Perpustakaan</title>
    @vite(['resources/css/app.css'])
</head>
<body>
    {{-- HEADER --}}
    <header class="header">
        <div class="container">
            <h1 class="header__title">PERDIG RADIT</h1>
            <p class="header__subtitle">Temukan buku favoritmu di sini</p>
        </div>
    </header>

    {{-- NAVBAR --}}
    <nav class="navbar">
        <div class="container navbar__inner">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('buku.index') }}" class="{{ request()->routeIs('buku.*') ? 'active' : '' }}">Daftar Buku</a>
        </div>
    </nav>

    {{-- KONTEN --}}
    <main class="container content">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="footer">
        <div class="container">
    </footer>
</body>
</html>
