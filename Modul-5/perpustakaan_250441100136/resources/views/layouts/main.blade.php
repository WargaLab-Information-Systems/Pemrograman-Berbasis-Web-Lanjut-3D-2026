<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Sistem Perpustakaan Digital'))</title>

    <!-- 1. CDN Bootstrap 5 CSS & Bootstrap Icons (WAJIB untuk view buku/index) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light font-sans antialiased">
    <div class="min-h-screen">
        {{-- Navbar --}}
        <nav class="bg-white border-b border-gray-200 mb-4 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        {{-- Logo / Brand Link --}}
                        <a href="{{ route('buku.index') }}" class="font-bold text-xl text-indigo-600 text-decoration-none">
                            📚 Perpustakaan Digital
                        </a>

                        {{-- Menu Utama Berdasarkan Permission --}}
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            @can('lihat daftar buku')
                                <a href="{{ route('buku.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium text-decoration-none {{ request()->routeIs('buku.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                    Daftar Buku
                                </a>
                            @endcan

                            @can('melihat riwayat peminjaman sendiri')
                                <a href="{{ route('peminjaman.riwayat') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium text-decoration-none {{ request()->routeIs('peminjaman.riwayat') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                    Riwayat Peminjaman
                                </a>
                            @endcan

                            @can('akses dashboard admin')
                                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium text-decoration-none {{ request()->routeIs('admin.dashboard') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                    Dashboard Admin
                                </a>
                            @endcan

                            @can('kelola data pengguna')
                                <a href="{{ route('users.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium text-decoration-none {{ request()->routeIs('users.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                    Kelola Pengguna
                                </a>
                            @endcan

                            @can('akses dashboard petugas')
                                <a href="{{ route('petugas.dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium text-decoration-none {{ request()->routeIs('petugas.dashboard') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                    Dashboard Petugas
                                </a>
                            @endcan

                            @can('kelola peminjaman')
                                <a href="{{ route('peminjaman.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium text-decoration-none {{ request()->routeIs('peminjaman.index') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                    Kelola Peminjaman
                                </a>
                            @endcan

                            @can('lihat laporan perpustakaan')
                                <a href="{{ route('laporan.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium text-decoration-none {{ request()->routeIs('laporan.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                    Laporan Perpustakaan
                                </a>
                            @endcan
                        </div>
                    </div>

                    {{-- User Dropdown / Profile & Logout --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        @auth
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-700 font-semibold">{{ Auth::user()->name }}</span>
                                <a href="{{ route('profile.edit') }}" class="text-sm text-gray-600 hover:text-indigo-600 text-decoration-none">Profil</a>
                                <form method="POST" action="{{ route('logout') }}" class="inline m-0">
                                    @csrf
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-800 bg-transparent border-0 p-0">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-indigo-600 text-decoration-none">Login</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        {{-- Main Content --}}
        <main class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- 2. CDN Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>