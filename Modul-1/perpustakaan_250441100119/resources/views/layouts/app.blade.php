<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Perpustakaan')</title>

    @vite(['resources/css/app.css'])
</head>

<body>

    <nav class="navbar">

        <div class="nav-container">

            <a href="{{ route('home') }}" class="logo">
                📚 Perpustakaan
            </a>

            <div class="nav-menu">

                <a href="{{ route('home') }}" class="nav-link">
                    Beranda
                </a>

                <a href="{{ route('buku.index') }}" class="nav-link">
                    Daftar Buku
                </a>

            </div>

        </div>

    </nav>

    <main class="content">

        @yield('content')

    </main>

    <footer class="footer">

        <p>
            © 2026 Perpustakaan 250441100119
        </p>

    </footer>

</body>

</html>