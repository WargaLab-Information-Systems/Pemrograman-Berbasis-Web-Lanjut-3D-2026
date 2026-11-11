<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Beranda') | Perpustakaan Digital</title>

    {{-- CSS dihubungkan lewat Vite, di layout utama --}}
    @vite('resources/css/app.css')
</head>
<body>

    {{-- Header --}}
    <header class="header">
        <h1>Perpustakaan Digital</h1>
        <p>Temukan buku favoritmu di sini</p>
    </header>

    {{-- Navbar --}}
    <nav class="navbar">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
        <a href="{{ route('buku.index') }}" class="{{ request()->routeIs('buku.*') ? 'active' : '' }}">Daftar Buku</a>
    </nav>

    {{-- Konten (diisi oleh halaman lewat @section('content')) --}}
    <main class="container">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="footer">
        <p>&copy; {{ date('Y') }} Perpustakaan Digital &mdash; perpustakaan_250441100038</p>
    </footer>

</body>
</html>