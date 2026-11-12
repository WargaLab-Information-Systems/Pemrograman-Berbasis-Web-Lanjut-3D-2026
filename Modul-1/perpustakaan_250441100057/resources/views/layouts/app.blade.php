<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('judul', 'Perpustakaan')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <header>
        <div class="header-isi">
            <h1>Perpustakaan</h1>
            <p>Sistem Informasi Perpustakaan</p>
        </div>
    </header>

    <nav>
        <div class="nav-isi">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('buku.index') }}">Daftar Buku</a>
        </div>
    </nav>

    <main>
        @yield('konten')
    </main>

    <footer>
        <p>&copy; 2026 Perpustakaan</p>
        <p>Sistem Informasi Perpustakaan</p>
    </footer>

</body>

</html>