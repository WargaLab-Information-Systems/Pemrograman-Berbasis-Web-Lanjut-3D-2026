<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Perpustakaan Digital')</title>

    {{-- CSS dan JS dihubungkan melalui Vite di layout utama --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    {{-- Header --}}
    <header class="site-header">
        <div class="container">
            <h1 class="site-header__logo">📚 Perpustakaan Digital</h1>
        </div>
    </header>

    {{-- Navbar --}}
    <nav class="site-navbar">
        <div class="container">
            <a href="{{ route('home') }}"
               class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('buku.index') }}"
               class="{{ request()->routeIs('buku.*') ? 'active' : '' }}">Daftar Buku</a>
        </div>
    </nav>

    {{-- Konten --}}
    <main class="site-content">
        <div class="container">
            @yield('content')
        </div>
    </main>

    {{-- Footer --}}
    <footer class="site-footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} Perpustakaan Digital. Dibuat dengan Laravel.</p>
        </div>
    </footer>

</body>
</html>
