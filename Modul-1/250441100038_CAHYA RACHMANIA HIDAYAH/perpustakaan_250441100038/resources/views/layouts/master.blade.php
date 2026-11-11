<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan')</title>
    @vite(['resources/css/app.css'])
</head>
<body>
    <header>
        <h2>Perpustakaan Kampus</h2>
        <nav>
            <a href="{{ route('home') }}">Beranda</a> | 
            <a href="{{ route('buku.index') }}">Daftar Buku</a>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; 2026 Aplikasi Perpustakaan. Dibuat untuk Tugas PBWL.</p>
    </footer>
</body>
</html>