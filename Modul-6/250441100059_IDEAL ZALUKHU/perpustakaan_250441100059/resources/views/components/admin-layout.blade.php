
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin - Perpustakaan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <div class="admin-wrapper">

        {{-- SIDEBAR KIRI --}}
        <aside class="admin-sidebar">

            {{-- IDENTITAS APLIKASI --}}
            <div class="admin-brand">
                <h2>PERPUSTAKAAN</h2>
                <p>Sistem Informasi</p>
            </div>

            {{-- ROLE PENGGUNA --}}
            <div class="admin-role">
                ADMINISTRATOR
            </div>

            {{-- MENU SIDEBAR --}}
            <nav class="admin-menu">

                @can('dashboard.admin')
                    <a href="{{ route('admin.dashboard') }}"
                       class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                @endcan

                @can('books.view')
                    <a href="{{ route('admin.buku.index') }}"
                       class="{{ request()->routeIs('admin.buku.*') ? 'active' : '' }}">
                        Manajemen Buku
                    </a>
                @endcan

                @can('users.manage')
                    <a href="{{ route('admin.users.index') }}"
                       class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        Manajemen Pengguna
                    </a>
                @endcan

                @can('loans.manage')
                    <a href="{{ route('admin.peminjaman.index') }}"
                       class="{{ request()->routeIs('admin.peminjaman.index', 'admin.peminjaman.create') ? 'active' : '' }}">
                        Manajemen Peminjaman
                    </a>
                @endcan

                @can('loans.history-own')
                    <a href="{{ route('admin.peminjaman.saya') }}"
                       class="{{ request()->routeIs('admin.peminjaman.saya') ? 'active' : '' }}">
                        Peminjaman Saya
                    </a>
                @endcan

                @can('reports.view')
                    <a href="{{ route('admin.laporan.index') }}"
                       class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                        Laporan
                    </a>
                @endcan

                @can('profile.view-own')
                    <a href="{{ route('profile.edit') }}"
                       class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        Profil Saya
                    </a>
                @endcan

            </nav>

            {{-- TOMBOL KELUAR --}}
            <div class="admin-sidebar-bottom">

                <form method="POST"
                    action="{{ route('logout') }}"
                    onsubmit="return confirm('Apakah Anda yakin ingin keluar dari akun?')">

                    @csrf

                <button type="submit" class="admin-logout-button" aria-label="Keluar" title="Keluar">
                    <svg class="admin-logout-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M14 4H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M8 12h11m-4-4 4 4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span>Keluar</span>
                </button>
            </form>

            </div>

        </aside>

        {{-- KONTEN KANAN --}}
        <main class="admin-main">

            {{-- TOPBAR --}}
            <header class="admin-topbar">

                <h3 class="admin-topbar-title">
                    Sistem Informasi Perpustakaan
                </h3>

                <span class="admin-user-name">
                    {{ Auth::user()->name }}
                </span>

            </header>

            {{-- ISI HALAMAN --}}
            <section class="admin-content">
                {{ $slot }}
            </section>

        </main>

    </div>

</body>
</html>
