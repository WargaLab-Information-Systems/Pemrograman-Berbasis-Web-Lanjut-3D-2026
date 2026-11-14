<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Perpustakaan Digital') }}</title>
    
    <!-- Bootstrap 5 & Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --bg-canvas: #f8fafc;
            --border-subtle: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --surface: #ffffff;
            --primary: #0f172a;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--bg-canvas);
            color: var(--text-main);
            letter-spacing: -0.01em;
        }

        /* Minimal Hairline Navigation */
        .site-nav {
            background-color: var(--surface);
            border-bottom: 1px solid var(--border-subtle);
        }

        .nav-link-custom {
            color: var(--text-muted);
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.4rem 0.75rem;
            border-radius: 6px;
            transition: all 0.15s ease-in-out;
            text-decoration: none;
        }

        .nav-link-custom:hover {
            color: var(--text-main);
            background-color: #f1f5f9;
        }

        .nav-link-badge {
            font-size: 0.75rem;
            letter-spacing: 0.04em;
            padding: 0.25rem 0.5rem;
            border: 1px solid var(--border-subtle);
            border-radius: 4px;
            background: #f8fafc;
        }

        /* Minimal Card & Panels */
        .panel {
            background: var(--surface);
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .panel-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-subtle);
            background: transparent;
        }

        .panel-body {
            padding: 1.25rem;
        }

        /* Refined Form Controls */
        .form-control, .form-select {
            border: 1px solid var(--border-subtle);
            border-radius: 6px;
            font-size: 0.875rem;
            padding: 0.55rem 0.75rem;
            color: var(--text-main);
            background-color: #ffffff;
        }

        .form-control:focus, .form-select:focus {
            border-color: #0f172a;
            box-shadow: 0 0 0 1px #0f172a;
            outline: none;
        }

        .form-label {
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 0.35rem;
        }

        /* Crisp Minimal Table */
        .table-minimal {
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-minimal th {
            background-color: #f8fafc;
            color: var(--text-muted);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-subtle);
            border-top: none;
        }

        .table-minimal td {
            padding: 0.875rem 1rem;
            font-size: 0.875rem;
            color: var(--text-main);
            border-bottom: 1px solid var(--border-subtle);
            vertical-align: middle;
        }

        .table-minimal tbody tr:last-child td {
            border-bottom: none;
        }

        .table-minimal tbody tr:hover td {
            background-color: #fcfdfe;
        }

        /* Status Badges */
        .status-pill {
            display: inline-flex;
            align-items: center;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.2rem 0.55rem;
            border-radius: 9999px;
            line-height: 1.2;
        }

        .status-menunggu { background: #fef3c7; color: #92400e; }
        .status-dipinjam { background: #e0f2fe; color: #0369a1; }
        .status-dikembalikan { background: #dcfce7; color: #166534; }
        .status-ditolak { background: #fee2e2; color: #991b1b; }

        /* Minimal Button System */
        .btn-brand {
            background: #0f172a;
            color: #ffffff;
            border: 1px solid #0f172a;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 6px;
            padding: 0.45rem 0.85rem;
            transition: all 0.15s ease;
        }
        .btn-brand:hover {
            background: #1e293b;
            color: #ffffff;
        }

        .btn-subtle {
            background: #ffffff;
            color: var(--text-main);
            border: 1px solid var(--border-subtle);
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 6px;
            padding: 0.45rem 0.85rem;
            transition: all 0.15s ease;
        }
        .btn-subtle:hover {
            background: #f1f5f9;
            color: var(--text-main);
        }

        .btn-action-danger {
            background: #ffffff;
            color: #b91c1c;
            border: 1px solid #fecaca;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 4px;
            padding: 0.3rem 0.6rem;
        }
        .btn-action-danger:hover {
            background: #fef2f2;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Top Minimal Navigation -->
    <header class="site-nav sticky-top">
        <div class="container-fluid px-4 py-2 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <a class="text-decoration-none fw-bold text-dark fs-6 d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                    <span style="display:inline-block; width:10px; height:10px; background:#0f172a; border-radius:2px;"></span>
                    <span>PustakaData</span>
                </a>
                <span class="text-muted small" style="opacity: 0.5;">/</span>
                <span class="text-muted small">Sistem Informasi</span>
            </div>

            <div class="d-flex align-items-center gap-2">
                @auth
                    @can('akses dashboard admin')
                        <a href="{{ route('admin.dashboard') }}" class="nav-link-custom">Dashboard</a>
                    @endcan

                    @can('akses dashboard petugas')
                        <a href="{{ route('petugas.dashboard') }}" class="nav-link-custom">Dashboard</a>
                    @endcan

                    @role('siswa')
                        <a href="{{ route('siswa.dashboard') }}" class="nav-link-custom">Dashboard</a>
                    @endrole

                    @can('lihat daftar buku')
                        <a href="{{ route('buku.index') }}" class="nav-link-custom">Katalog</a>
                    @endcan

                    @can('kelola data pengguna')
                        <a href="{{ route('admin.users') }}" class="nav-link-custom">Pengguna</a>
                    @endcan

                    @can('kelola peminjaman')
                        <a href="{{ route('peminjaman.index') }}" class="nav-link-custom">Sirkulasi</a>
                    @endcan

                    @can('lihat laporan perpustakaan')
                        <a href="{{ route('laporan.index') }}" class="nav-link-custom">Laporan</a>
                    @endcan

                    @can('melihat riwayat peminjaman sendiri')
                        <a href="{{ route('peminjaman.riwayat.sendiri') }}" class="nav-link-custom">Riwayat Pinjam</a>
                    @endcan

                    @can('lihat profil sendiri')
                        <a href="{{ route('profile.edit') }}" class="nav-link-custom d-flex align-items-center gap-2 ms-2">
                            <span>{{ Auth::user()->name }}</span>
                            <span class="nav-link-badge">{{ strtoupper(Auth::user()->getRoleNames()->first() ?? 'User') }}</span>
                        </a>
                    @endcan

                    <form method="POST" action="{{ route('logout') }}" class="d-inline mb-0 ms-2">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-subtle text-danger border-0 py-1 px-2" style="font-size:0.8125rem;">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-link-custom">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-brand text-decoration-none">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-grow-1 py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert border-0 rounded-2 py-2 px-3 mb-3 d-flex justify-content-between align-items-center" style="background:#f0fdf4; border:1px solid #bbf7d0 !important; color:#166534; font-size:0.875rem;">
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close shadow-none" style="font-size: 0.65rem;" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert border-0 rounded-2 py-2 px-3 mb-3 d-flex justify-content-between align-items-center" style="background:#fef2f2; border:1px solid #fecaca !important; color:#991b1b; font-size:0.875rem;">
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close shadow-none" style="font-size: 0.65rem;" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        @yield('content')
    </main>

    <!-- Minimal Footer -->
    <footer class="py-3 border-top bg-white mt-auto">
        <div class="container d-flex justify-content-between align-items-center text-muted" style="font-size: 0.75rem;">
            <div>2026 Perpustakaan Digital · 250441100113</div>
            <div></div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>