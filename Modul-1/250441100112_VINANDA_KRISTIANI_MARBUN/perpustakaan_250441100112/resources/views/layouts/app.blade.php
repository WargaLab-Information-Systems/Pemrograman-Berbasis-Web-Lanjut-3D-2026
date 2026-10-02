<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Perpustakaan Digital')</title>

    @vite(['resources/css/app.css'])
</head>

<body>

    <!-- HEADER -->
    <header class="header">
        <div class="container">
            <h1>📚 Perpustakaan Digital</h1>
            <p>Membaca adalah jendela dunia</p>
        </div>
    </header>


    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="container nav-container">

            <a href="{{ route('home') }}"
               class="{{ request()->routeIs('home') ? 'active' : '' }}">
                Beranda
            </a>

            <a href="{{ route('buku.index') }}"
               class="{{ request()->routeIs('buku.*') ? 'active' : '' }}">
                Daftar Buku
            </a>

        </div>
    </nav>


    <!-- KONTEN -->
    <main class="container content">

        @yield('content')

    </main>


    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">

            <p>
                &copy; 2026 Perpustakaan Digital
            </p>

            <p>
                Laravel 13 - Praktikum PBWL
            </p>

        </div>
    </footer>

</body>

</html>