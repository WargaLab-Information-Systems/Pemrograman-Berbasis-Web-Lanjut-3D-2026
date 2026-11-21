<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Perpustakaan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen bg-gray-100 font-sans">
    <aside class="w-64 bg-white border-r shadow-md">
        <div class="p-6 text-center border-b">
            <h1 class="text-2xl font-bold text-blue-600">Perpustakaan</h1>
        </div>
        <nav class="p-4 space-y-2">
            
            <!-- Menu Beranda -->
            @role('admin')
                <a href="{{ url('/admin/dashboard') }}" 
                   class="block p-3 rounded font-bold transition-colors duration-200 {{ request()->is('admin/dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100 hover:text-blue-600' }}">
                    Beranda Admin
                </a>
            @endrole
            
            @role('petugas')
                <a href="{{ url('/petugas/dashboard') }}" 
                   class="block p-3 rounded font-bold transition-colors duration-200 {{ request()->is('petugas/dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100 hover:text-blue-600' }}">
                    Beranda Petugas
                </a>
            @endrole
            
            @role('siswa')
                <a href="{{ url('/dashboard') }}" 
                   class="block p-3 rounded font-bold transition-colors duration-200 {{ request()->is('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100 hover:text-blue-600' }}">
                    Beranda Siswa
                </a>
            @endrole

            <!-- Menu Umum -->
            <a href="{{ url('/buku') }}" 
               class="block p-3 rounded font-bold transition-colors duration-200 {{ request()->is('buku') || request()->is('buku/*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100 hover:text-blue-600' }}">
                Daftar Buku
            </a>

            <!-- Menu Sirkulasi -->
            @hasanyrole('admin|petugas')
                <a href="{{ url('/peminjaman') }}" 
                   class="block p-3 rounded font-bold transition-colors duration-200 {{ request()->is('peminjaman') || request()->is('peminjaman/*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100 hover:text-blue-600' }}">
                    Kelola Peminjaman
                </a>
                <a href="{{ url('/laporan') }}" 
                   class="block p-3 rounded font-bold transition-colors duration-200 {{ request()->is('laporan') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100 hover:text-blue-600' }}">
                    Laporan Sirkulasi
                </a>
            @endhasanyrole

            @role('siswa')
                <a href="{{ url('/riwayat') }}" 
                   class="block p-3 rounded font-bold transition-colors duration-200 {{ request()->is('riwayat') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100 hover:text-blue-600' }}">
                    Riwayat Saya
                </a>
            @endrole

            <!-- Menu Akun -->
            @role('admin')
                <a href="{{ url('/admin/pengguna') }}" 
                   class="block p-3 rounded font-bold transition-colors duration-200 mt-4 {{ request()->is('admin/pengguna') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100 hover:text-blue-600' }}">
                    Kelola Akun Pengguna
                </a>
            @endrole
        </nav>
    </aside>

    <div class="flex-1">
        @include('layouts.navigation')

        @if (isset($header))
            <header class="bg-white shadow p-4">
                {{ $header }}
            </header>
        @endif

        <main class="p-8">
            {{ $slot }}
        </main>
    </div>
</body>
</html>