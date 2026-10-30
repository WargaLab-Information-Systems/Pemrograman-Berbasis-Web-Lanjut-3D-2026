<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Perpustakaan')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <!-- Header + Navbar -->
    <header class="header">
        <div class="container navbar">
            <a href="{{ route('home') }}" class="logo">
                📚 Perpustakaan
            </a>

            <nav>
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('buku.index') }}">Daftar Buku</a>
            </nav>
        </div>
    </header>

    <!-- Konten -->
    <main class="container content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>© 2026 Perpustakaan. Semua hak dilindungi.</p>
    </footer>

</body>
</html>