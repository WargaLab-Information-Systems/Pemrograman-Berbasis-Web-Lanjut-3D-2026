<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Beranda') - Perpustakaan Digital</title>
    @vite('resources/css/app.css')
</head>
<body>

    {{-- HEADER --}}
    <header class="header">
        <div class="container">
            <h1 class="header__title"> Perpustakaan Digital</h1>
            <p class="header__subtitle">Jendela dunia ada di genggamanmu</p>
        </div>
    </header>

    {{-- NAVBAR --}}
    <nav class="navbar">
        <div class="container navbar__inner">
            <a href="{{ route('home') }}"
               class="navbar__link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('buku.index') }}"
               class="navbar__link {{ request()->routeIs('buku.*') ? 'active' : '' }}">Daftar Buku</a>
        </div>
    </nav>

    {{-- KONTEN --}}
    <main class="container content">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="footer">
        <div class="container">
            &copy; {{ date('Y') }} Perpustakaan Digital &mdash; 250441100029
        </div>
    </footer>

</body>
</html>