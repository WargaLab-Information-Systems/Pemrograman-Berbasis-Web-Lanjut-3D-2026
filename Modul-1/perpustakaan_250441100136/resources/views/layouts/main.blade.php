<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan Digital')</title>

    <!-- Menghubungkan CSS via Vite -->
    @vite(['resources/css/app.css'])
</head>
<body>

    <!-- Header & Navbar -->
    <header class="navbar-header">
        <div class="container header-container">
            <a href="{{ route('home') }}" class="brand-logo">📚 E-Pustaka UTM</a>
            <nav class="nav-links">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('buku.index') }}" class="{{ request()->routeIs('buku.*') ? 'active' : '' }}">Daftar Buku</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content container">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} Sistem Perpustakaan Digital - 250441100136. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>