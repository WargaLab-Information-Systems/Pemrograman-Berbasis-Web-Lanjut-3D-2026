<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan Digital')</title>

    {{-- 16. Hubungkan CSS melalui Vite di layout utama --}}
    @vite('resources/css/app.css')
</head>
<body>

    {{-- 7a. Header --}}
    <header class="site-header">
        <div class="container">
            <h1 class="site-header__logo">📚 Perpustakaan Digital</h1>
        </div>
    </header>

    {{-- 7b. Navbar --}}
    <nav class="site-navbar">
        <div class="container">
            <a href="{{ route('home') }}"
               class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('buku.index') }}"
               class="{{ request()->routeIs('buku.*') ? 'active' : '' }}">Daftar Buku</a>
        </div>
    </nav>

    {{-- 7c. Konten (diisi tiap halaman lewat @yield) --}}
    <main class="site-content">
        <div class="container">
            @yield('content')
        </div>
    </main>

    {{-- 7d. Footer --}}
    <footer class="site-footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} Perpustakaan Digital. Dibuat dengan Laravel.</p>
        </div>
    </footer>

</body>
</html>
