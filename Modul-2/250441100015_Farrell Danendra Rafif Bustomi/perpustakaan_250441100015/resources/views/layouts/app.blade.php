<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <div class="logo">
                <a href="{{ route('home') }}">Perpustakaan</a>
            </div>
            <ul class="nav-menu">
                <li>
                    <a href="{{ route('home') }}">Beranda</a>
                </li>
                <li>
                    <a href="{{ route('buku.index') }}">Daftar Buku</a>
                </li>
            </ul>
        </nav>
    </header>
    <main class="main-content">
        @yield('content')
    </main>
    <footer class="footer">
        <div class="footer-content">
            <h3>Perpustakaan Digital</h3>
            <p>Menyediakan berbagai koleksi buku untuk mendukung kebutuhan membaca dan belajar.</p>
            <div class="footer-line"></div>
            <p class="copyright">
                &copy; 2026 Perpustakaan Digital. All Rights Reserved.
            </p>
        </div>
    </footer>
    <script>
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    </script>
</body>
</html>