<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Beranda') | Perpustakaan Digital</title>

    {{-- CSS langsung di sini agar tidak bergantung pada Vite --}}
    <style>
        :root {
            --hijau: #2f7a4c; --hijau-tua: #1f5535; --hijau-muda: #e8f3ec;
            --abu: #6b7280; --garis: #e5e7eb; --latar: #f3f6f4; --teks: #1f2937;
            --merah: #d9534f; --kuning: #f0ad4e; --biru: #2563eb;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', system-ui, -apple-system, Roboto, sans-serif; margin: 0; background: var(--latar); color: var(--teks); line-height: 1.5; }
        a { color: var(--hijau); }
        .wrap { max-width: 1200px; margin: 0 auto; padding: 0 20px; }

        /* ---------- Topbar & menu ---------- */
        .topbar { background: linear-gradient(135deg, var(--hijau-tua), var(--hijau)); color: #fff; box-shadow: 0 2px 8px rgba(0,0,0,.15); position: sticky; top: 0; z-index: 50; }
        .topbar-in { display: flex; align-items: center; justify-content: space-between; gap: 15px; padding-top: 12px; padding-bottom: 12px; flex-wrap: wrap; }
        .brand { display: flex; align-items: center; gap: 10px; color: #fff; text-decoration: none; font-size: 20px; font-weight: 700; }
        .brand .logo { background: rgba(255,255,255,.18); border-radius: 10px; padding: 4px 8px; font-size: 20px; }
        .user { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .user form { margin: 0; }
        .chip { background: rgba(255,255,255,.18); padding: 6px 14px; border-radius: 999px; font-size: 14px; }
        .btn-top { background: #fff; color: var(--hijau-tua); border: 0; padding: 7px 16px; border-radius: 999px; font-weight: 600; font-size: 14px; cursor: pointer; text-decoration: none; font-family: inherit; }
        .btn-top:hover { background: var(--hijau-muda); }
        .btn-top.keluar { background: transparent; color: #fff; border: 1px solid rgba(255,255,255,.6); }
        .btn-top.keluar:hover { background: var(--merah); border-color: var(--merah); }
        .menu { display: flex; gap: 4px; flex-wrap: wrap; padding-bottom: 10px; }
        .menu a { color: rgba(255,255,255,.9); text-decoration: none; padding: 7px 14px; border-radius: 8px; font-size: 14px; font-weight: 600; transition: .2s; }
        .menu a:hover { background: rgba(255,255,255,.15); color: #fff; }
        .menu a.active { background: #fff; color: var(--hijau-tua); }

        /* ---------- Konten ---------- */
        .container { padding: 28px 20px; max-width: 1200px; margin: 0 auto; min-height: 68vh; }
        h2.page-title, .container > h2:first-child { color: var(--hijau-tua); margin-top: 0; font-size: 26px; }
        h3 { color: var(--hijau-tua); }
        .footer { text-align: center; color: var(--abu); padding: 22px; font-size: 13px; border-top: 1px solid var(--garis); background: #fff; margin-top: 30px; }

        /* ---------- Alert ---------- */
        .alert { padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; background: #fff8e1; border: 1px solid #ffe08a; }
        .alert-success { background: #e6f6ec; color: #14532d; border: 1px solid #b7e4c7; }
        .alert-error { background: #fdecec; color: #7f1d1d; border: 1px solid #f5c2c2; }

        /* ---------- Kartu ringkasan ---------- */
        .card-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .card-box { background: #fff; border-radius: 14px; padding: 20px; box-shadow: 0 1px 4px rgba(0,0,0,.08); border-left: 5px solid var(--hijau); color: var(--abu); font-size: 14px; }
        .card-box .angka { font-size: 32px; font-weight: 800; color: var(--hijau-tua); line-height: 1.1; }

        /* ---------- Tabel ---------- */
        .tbl, .styled-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.08); margin-bottom: 26px; }
        .tbl th, .styled-table th { background: var(--hijau); color: #fff; text-align: left; padding: 12px 16px; font-size: 14px; }
        .tbl td, .styled-table td { padding: 12px 16px; border-bottom: 1px solid var(--garis); font-size: 14px; }
        .tbl tbody tr:hover, .styled-table tbody tr:hover { background: var(--hijau-muda); }
        .tbl tbody tr:last-child td, .styled-table tbody tr:last-child td { border-bottom: 0; }
        @media (max-width: 800px) { .tbl, .styled-table { display: block; overflow-x: auto; white-space: nowrap; } }

        /* ---------- Tombol ---------- */
        .tombol, .btn { display: inline-block; padding: 8px 16px; border: 0; border-radius: 8px; cursor: pointer; font-weight: 600; color: #fff !important; background: var(--hijau); text-decoration: none; font-size: 14px; font-family: inherit; transition: .2s; }
        .tombol:hover, .btn:hover { filter: brightness(1.1); transform: translateY(-1px); }
        .tombol-abu, .btn-secondary { background: var(--abu); }
        .tombol-kuning, .btn-warning { background: var(--kuning); padding: 6px 12px; }
        .tombol-merah, .btn-danger { background: var(--merah); padding: 6px 12px; }
        .btn-primary { background: var(--hijau); }
        .tombol-biru { background: var(--biru); }

        /* ---------- Badge status ---------- */
        .badge-st { padding: 3px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; color: #fff; background: var(--abu); }
        .st-diajukan { background: var(--kuning); } .st-dipinjam { background: var(--biru); }
        .st-dikembalikan { background: var(--hijau); } .st-ditolak { background: var(--merah); }

        /* ---------- Form ---------- */
        .form-container, .panel { background: #fff; padding: 22px; border-radius: 14px; box-shadow: 0 1px 4px rgba(0,0,0,.08); margin-bottom: 24px; }
        .form-row { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 14px; }
        .form-control, .form-grup input, .form-grup select { padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px; background: #fff; }
        .form-control { flex: 1; min-width: 150px; }
        .form-control:focus, .form-grup input:focus, .form-grup select:focus { outline: 2px solid var(--hijau); border-color: var(--hijau); }
        .form-grup { margin-bottom: 14px; } .form-grup label { display: block; font-weight: 600; margin-bottom: 5px; font-size: 14px; }
        .form-grup input, .form-grup select { width: 100%; max-width: 420px; }
        .filter { display: flex; gap: 14px; flex-wrap: wrap; align-items: flex-end; }
        .filter .form-grup { margin: 0; } .filter input, .filter select { max-width: 200px; }

        /* ---------- Detail buku & beranda ---------- */
        .detail { background: #fff; padding: 26px; border-radius: 14px; box-shadow: 0 1px 4px rgba(0,0,0,.08); max-width: 700px; }
        .detail table { width: 100%; border-collapse: collapse; margin: 14px 0 18px; }
        .detail th { text-align: left; width: 35%; color: var(--abu); padding: 9px 0; border-bottom: 1px solid var(--garis); }
        .detail td { padding: 9px 0; border-bottom: 1px solid var(--garis); }
        .hero { background: linear-gradient(135deg, var(--hijau-muda), #fff); border-radius: 18px; box-shadow: 0 1px 6px rgba(0,0,0,.08); }

        @media print { .topbar, .footer { display: none; } body { background: #fff; } }
    </style>
</head>
<body>

    <header class="topbar">
        <div class="wrap topbar-in">
            <a class="brand" href="{{ route('home') }}"><span class="logo"></span><span>Perpustakaan Digital</span></a>

            <div class="user">
                @auth
                    <span class="chip">
                        {{ Auth::user()->name }} &middot;
                        @role('admin') Admin @endrole @role('petugas') Petugas @endrole @role('siswa') Siswa @endrole
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-top keluar">Logout</button>
                    </form>
                @else
                    <a class="btn-top" href="{{ route('login') }}">Login</a>
                    <a class="btn-top keluar" href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </div>

        <nav class="wrap menu">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>

            @auth
                {{-- Menu tampil sesuai permission --}}
                @can('akses dashboard admin')
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard Admin</a>
                @endcan
                @can('akses dashboard petugas')
                    <a href="{{ route('petugas.dashboard') }}" class="{{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">Dashboard Petugas</a>
                @endcan
                @can('lihat daftar buku')
                    <a href="{{ route('buku.index') }}" class="{{ request()->routeIs('buku.*') ? 'active' : '' }}">Daftar Buku</a>
                @endcan
                @can('kelola data pengguna')
                    <a href="{{ route('pengguna.index') }}" class="{{ request()->routeIs('pengguna.*') ? 'active' : '' }}">Kelola Pengguna</a>
                @endcan
                @can('kelola peminjaman')
                    <a href="{{ route('peminjaman.index') }}" class="{{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">Peminjaman</a>
                @endcan
                @can('kelola pengembalian')
                    <a href="{{ route('pengembalian.index') }}" class="{{ request()->routeIs('pengembalian.*') ? 'active' : '' }}">Pengembalian</a>
                @endcan
                @can('lihat laporan perpustakaan')
                    <a href="{{ route('laporan.index') }}" class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}">Laporan</a>
                @endcan
                @can('melihat riwayat peminjaman sendiri')
                    <a href="{{ route('riwayat.index') }}" class="{{ request()->routeIs('riwayat.*') ? 'active' : '' }}">Riwayat Saya</a>
                @endcan
                @can('lihat profil sendiri')
                    <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">Profil</a>
                @endcan
            @endauth
        </nav>
    </header>

    <main class="container">
        @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if (session('error')) <div class="alert alert-error">{{ session('error') }}</div> @endif
        @if ($errors->any())
            <div class="alert alert-error"><ul style="margin:0;padding-left:18px;">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        @yield('content')
    </main>

    <footer class="footer">
        &copy; {{ date('Y') }} Perpustakaan Digital &mdash; perpustakaan_250441100038
    </footer>

</body>
</html>
