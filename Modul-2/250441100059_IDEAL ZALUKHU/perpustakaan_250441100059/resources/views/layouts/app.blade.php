<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Perpustakaan Digital')</title>

    @vite('resources/css/app.css')
</head>

<body>

    <header>
        <div class="header-content">
            <img src="{{ asset('images/logo_perpus.webp') }}" alt="Logo perpustakaan">
        
            <div>
                <h1>Perpustakaan Digital</h1>
            <p>Sistem Informasi Buku</p>
            </div>
            
        </div>

    </header>

    <nav>
        <a href="{{ route('home') }}">Beranda</a> 
        <a href="{{ route('buku.index') }}">Daftar Buku</a>
    </nav>

    <hr>

    <main>
        @yield('content')
    </main>

    <hr>

    <footer>
        <p>&copy; 2026 Perpustakaan Digital</p>
    </footer>

</body>
</html>