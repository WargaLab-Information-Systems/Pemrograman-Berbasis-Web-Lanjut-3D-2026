<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('judul', 'Perpustakaan')</title>
    @vite(['resources/css/app.css'])
</head>
<body>
    <header>
        <h1>SISTEM INFORMASI PERPUSTAKAAN</h1>
    </header>

    <nav>
        <a href="{{ route('home') }}">Beranda</a>
        <a href="{{ route('buku.index') }}">Daftar Buku</a>
    </nav>

    <main>
        @yield('konten')
    </main>

    <footer>
        <p>© 2026 Sistem Informasi</p>
    </footer>
</body>
</html>