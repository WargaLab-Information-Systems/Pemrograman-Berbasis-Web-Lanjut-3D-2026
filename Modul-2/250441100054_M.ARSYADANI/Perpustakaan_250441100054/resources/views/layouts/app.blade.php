<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Beranda') | Perpustakaan Kita</title>

    {{-- CSS dipanggil lewat Vite (hanya di layout utama) --}}
    @vite('resources/css/app.css')
</head>
<body>

    {{-- HEADER --}}
    <header class="site-header">
        <div class="container">
            <a href="{{ route('home') }}" class="brand">Perpustakaan Kita</a>
            <p class="tagline">Katalog buku sederhana untuk semua pembaca</p>
        </div>
    </header>

    {{-- NAVBAR --}}
    <nav class="navbar" aria-label="Navigasi utama">
        <div class="container">
            <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>Beranda</a>
            <a href="{{ route('buku.index') }}" @class(['active' => request()->routeIs('buku.*')])>Daftar Buku</a>
        </div>
    </nav>

    {{-- KONTEN --}}
    <main class="container content">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="site-footer">
        <div class="container">
            &copy; {{ date('Y') }} Perpustakaan Kita
        </div>
    </footer>

</body>
</html>
