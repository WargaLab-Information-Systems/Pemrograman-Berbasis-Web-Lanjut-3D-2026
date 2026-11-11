
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Petugas - Perpustakaan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="admin-wrapper">

        <!-- SIDEBAR PETUGAS -->
        <aside class="admin-sidebar">

            <div class="admin-brand">
                <h2>PERPUSTAKAAN</h2>
                <p>Sistem Informasi</p>
            </div>

            <div class="admin-role">
                PETUGAS
            </div>

            <nav class="admin-menu">

                @can('dashboard.petugas')
                    <a href="{{ route('petugas.dashboard') }}"
                       class="{{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                @endcan

                @can('books.view')
                    <a href="{{ route('petugas.buku.index') }}"
                       class="{{ request()->routeIs('petugas.buku.*') ? 'active' : '' }}">
                        Manajemen Buku
                    </a>
                @endcan

                @can('loans.manage')
                    <a href="{{ route('petugas.peminjaman.index') }}"
                       class="{{ request()->routeIs('petugas.peminjaman.index', 'petugas.peminjaman.create') ? 'active' : '' }}">
                        Manajemen Peminjaman
                    </a>
                @endcan

                @can('loans.history-own')
                    <a href="{{ route('petugas.peminjaman.saya') }}"
                       class="{{ request()->routeIs('petugas.peminjaman.saya') ? 'active' : '' }}">
                        Peminjaman Saya
                    </a>
                @endcan

                @can('reports.view')
                    <a href="{{ route('petugas.laporan.index') }}"
                       class="{{ request()->routeIs('petugas.laporan.*') ? 'active' : '' }}">
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

        <!-- KONTEN UTAMA -->
        <main class="admin-main">

            <header class="admin-topbar">
                <h3 class="admin-topbar-title">
                    Sistem Informasi Perpustakaan
                </h3>

                <span class="admin-user-name">
                    {{ Auth::user()->name }}
                </span>
            </header>

            <section class="admin-content">
                {{ $slot }}
            </section>

        </main>

    </div>
</body>
</html>
