<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Digital UTM</title>
    @vite(["resources/css/app.css"])
</head>
<body>
    <header>
        <h1>Perpustakaan Digital UTM</h1>
        <nav>
            <a href="{{ route("home") }}">Beranda</a>
            <a href="{{ route("buku.index") }}">Daftar Buku</a>
        </nav>
    </header>

    <main>
        @yield("content")
    </main>

    <footer>
        <p>&copy; 2026 Perpustakaan Digital. All rights reserved.</p>
    </footer>
</body>
</html>