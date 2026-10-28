<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Perpustakaan</title>
    @vite(['resources/css/app.css'])
</head>
<body>
    <header>
        <h1>Perpustakaan Kampus</h1>
        <nav>
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('buku.index') }}">Daftar Buku</a>
        </nav>
    </header>

    <main class="container">
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} Perpustakaan_250441100113</p>
    </footer>
</body>
</html>