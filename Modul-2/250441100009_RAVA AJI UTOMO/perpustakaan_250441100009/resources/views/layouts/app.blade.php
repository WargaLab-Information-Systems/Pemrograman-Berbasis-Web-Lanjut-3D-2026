<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('judul', 'Perpustakaan Online')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body>

    <header class="header">
        <h1>Perpustakaan Onlineku</h1>
    </header>

    <nav class="navbar">
        <a href="{{ route('home') }}">Beranda</a>
        <a href="{{ route('buku.index') }}">Daftar Buku</a>
    </nav>

    <main class="konten">
        @yield('konten')
    </main>

    <footer class="footer">
        <p>&copy; {{ date('Y') }} Perpustakaan Online - Tugas Praktikum Laravel</p>
    </footer>

</body>
</html>