<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - Perpustakaan Digital</title>

    @vite(['resources/css/app.css'])
</head>

<body class="dashboard-page">

    <header class="header">
        <div class="container">
            <h1>Perpustakaan Digital</h1>
            <p>Jelajahi berbagai koleksi buku dan temukan bacaan yang sesuai dengan minatmu.</p>
        </div>
    </header>

    <nav class="navbar auth-navbar">
        <div class="container">
            <div class="auth-nav-left">
                <a href="{{ route('dashboard') }}"
                   class="{{ request()->routeIs('dashboard', 'admin.dashboard', 'petugas.dashboard', 'siswa.dashboard') ? 'active' : '' }}">Dashboard</a>

                @can('lihat daftar buku')
                    <a href="{{ route('buku.index') }}"
                       class="{{ request()->routeIs('buku.*') ? 'active' : '' }}">Daftar Buku</a>
                @endcan

                @can('kelola data buku')
                    <a href="{{ route('kelola.buku.index') }}"
                       class="{{ request()->routeIs('kelola.buku.*') ? 'active' : '' }}">Kelola Buku</a>
                @endcan

                @can('kelola data pengguna')
                    <a href="{{ route('admin.users.index') }}"
                       class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Pengguna</a>
                @endcan

                @can('kelola peminjaman')
                    <a href="{{ route('kelola.peminjaman.index') }}"
                       class="{{ request()->routeIs('kelola.peminjaman.*') ? 'active' : '' }}">Peminjaman</a>
                @endcan

                @can('kelola pengembalian')
                    <a href="{{ route('kelola.pengembalian.index') }}"
                       class="{{ request()->routeIs('kelola.pengembalian.*') ? 'active' : '' }}">Pengembalian</a>
                @endcan

                @can('lihat laporan perpustakaan')
                    <a href="{{ route('laporan.index') }}"
                       class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}">Laporan</a>
                @endcan

                @can('melihat status peminjaman sendiri')
                    <a href="{{ route('peminjaman.status') }}"
                       class="{{ request()->routeIs('peminjaman.status') ? 'active' : '' }}">Peminjaman Saya</a>
                @endcan

                @can('melihat riwayat peminjaman sendiri')
                    <a href="{{ route('peminjaman.riwayat') }}"
                       class="{{ request()->routeIs('peminjaman.riwayat', 'peminjaman.show') ? 'active' : '' }}">Riwayat</a>
                @endcan
            </div>

            <details class="user-menu">
                <summary class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">{{ Auth::user()->name }} &#9662;</summary>
                <div class="user-menu-list">
                    @can('lihat profil sendiri')
                        <a href="{{ route('profile.edit') }}">Profile</a>
                    @endcan

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </details>
        </div>
    </nav>

    <main class="dashboard-main">
        @include('partials.flash')
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} Perpustakaan Digital</p>
        </div>
    </footer>

    <script>
        // Tutup dropdown nama pengguna saat klik di luar menu
        document.addEventListener('click', function (e) {
            var menu = document.querySelector('.user-menu');
            if (menu && menu.open && !menu.contains(e.target)) {
                menu.open = false;
            }
        });
    </script>

</body>
</html>
