<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Perpustakaan Digital')</title>

    @vite(['resources/css/app.css'])
</head>

<body>

    <header class="header">
        <div class="container">
            <h1>Perpustakaan Digital</h1>
            <p>Tempat menemukan berbagai macam buku</p>
        </div>
    </header>

    <nav class="navbar">
        <div class="container nav-content">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('buku.index') }}">Daftar Buku</a>
        </div>
    </nav>

    <main class="container content">
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} Perpustakaan Digital</p>
        </div>
    </footer>

</body>
</html> 