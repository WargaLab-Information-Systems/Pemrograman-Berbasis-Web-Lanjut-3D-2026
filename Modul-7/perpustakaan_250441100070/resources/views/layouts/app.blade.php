<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Perpustakaan Digital')</title>

    {{-- CSS dan JS dihubungkan melalui Vite di layout utama --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    {{-- Header --}}
    <header class="site-header">
        <div class="container">
            <h1 class="site-header__logo">📚 Perpustakaan Digital</h1>
        </div>
    </header>

    {{-- Navbar: menu tampil sesuai hak akses (@can / @role) --}}
    <nav class="site-navbar">
        <div class="container">
            <a href="{{ route('home') }}"
               class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>

            @auth
                @can('lihat daftar buku')
                    <a href="{{ route('buku.index') }}"
                       class="{{ request()->routeIs('buku.*') ? 'active' : '' }}">Daftar Buku</a>
                @endcan

                @can('akses dashboard admin')
                    <a href="{{ route('admin.dashboard') }}"
                       class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard Admin</a>
                @endcan

                @can('akses dashboard petugas')
                    <a href="{{ route('petugas.dashboard') }}"
                       class="{{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">Dashboard Petugas</a>
                @endcan

                @can('kelola peminjaman')
                    <a href="{{ route('peminjaman.index') }}"
                       class="{{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">Peminjaman</a>
                @endcan

                @can('kelola pengembalian')
                    <a href="{{ route('pengembalian.index') }}"
                       class="{{ request()->routeIs('pengembalian.*') ? 'active' : '' }}">Pengembalian</a>
                @endcan

                @can('lihat laporan perpustakaan')
                    <a href="{{ route('laporan.index') }}"
                       class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}">Laporan</a>
                @endcan

                @can('kelola data pengguna')
                    <a href="{{ route('admin.users.index') }}"
                       class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Pengguna</a>
                @endcan

                @can('melihat riwayat peminjaman sendiri')
                    <a href="{{ route('riwayat.show', auth()->user()) }}"
                       class="{{ request()->routeIs('riwayat.*') ? 'active' : '' }}">Riwayat Saya</a>
                @endcan
            @endauth

            <span class="nav-spacer"></span>

            @guest
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endguest

            @auth
                @can('lihat profil sendiri')
                    <a href="{{ route('profile.edit') }}"
                       class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">{{ auth()->user()->name }}</a>
                @endcan

                @role('admin')
                    <span class="badge badge-role">admin</span>
                @endrole
                @role('petugas')
                    <span class="badge badge-role">petugas</span>
                @endrole
                @role('siswa')
                    <span class="badge badge-role">siswa</span>
                @endrole

                <form method="POST" action="{{ route('logout') }}" class="inline-form">
                    @csrf
                    <button type="submit" class="link-button">Logout</button>
                </form>
            @endauth
        </div>
    </nav>

    {{-- Konten --}}
    <main class="site-content">
        <div class="container">
            @if (session('sukses'))
                <div class="alert alert-success" role="status">{{ session('sukses') }}</div>
            @endif

            @if (session('galat'))
                <div class="alert alert-danger" role="alert">{{ session('galat') }}</div>
            @endif

            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger" role="alert">
                    @foreach ($errors->all() as $pesan)
                        <div>{{ $pesan }}</div>
                    @endforeach
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    {{-- Footer --}}
    <footer class="site-footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} Perpustakaan Digital. Dibuat dengan Laravel.</p>
        </div>
    </footer>

</body>
</html>
