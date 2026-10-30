<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Perpustakaan Digital')</title>

    @vite('resources/css/app.css')
</head>

<body>

    <header class="header">
        <div class="container navbar">

            <a href="{{ route('home') }}" class="logo">
                Perpustakaan
            </a>

            <nav class="nav-menu">
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('buku.index') }}">Daftar Buku</a>
            </nav>

        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <center>
            <p>© 2026 Perpustakaan Digital</p>
            </center>
            
        </div>
    </footer>

</body>
</html>