<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul', 'Perpustakaan Online')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <header class="header">
        <h1>Perpustakaan Onlineku</h1>
    </header>

    <nav class="navbar">
        <a href="{{ route('home') }}">Beranda</a>
        <a href="{{ route('buku.index') }}">Daftar Buku</a>

        @auth
            @role('admin')
                <a href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                <a href="{{ route('admin.users') }}">Kelola Pengguna</a>
                <a href="{{ route('admin.peminjaman') }}">Data Peminjaman</a>
                <a href="{{ route('admin.laporan') }}">Laporan</a>
            @endrole

            @role('petugas')
                <a href="{{ route('petugas.dashboard') }}">Dashboard Petugas</a>
                <a href="{{ route('petugas.peminjaman') }}">Proses Peminjaman</a>
            @endrole

            @role('siswa')
                <a href="{{ route('siswa.riwayat') }}">Riwayat Peminjaman</a>
            @endrole

            <a href="{{ route('profile.edit') }}">Profil ({{ Auth::user()->name }})</a>

            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                    Keluar
                </a>
            </form>
        @else
            <a href="{{ route('login') }}" style="margin-left: auto;">Masuk</a>
            <a href="{{ route('register') }}">Daftar</a>
        @endauth
    </nav>

    <main class="konten">
        @if (session('status'))
            <div class="pesan-status pesan-sukses">
                {{ session('status') }}
            </div>
        @endif

        @yield('konten')
        {{ $slot ?? '' }}
    </main>

    <footer class="footer">
        <p>&copy; {{ date('Y') }} Perpustakaan Online - Tugas Praktikum Laravel</p>
    </footer>

</body>
</html>
