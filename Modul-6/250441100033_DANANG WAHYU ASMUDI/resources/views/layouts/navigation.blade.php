<header class="bg-[#111827] text-white shadow-md">
    <div class="max-w-6xl mx-auto px-6 py-4 flex flex-wrap justify-between items-center gap-4">
        <div>
            <a href="/" class="text-2xl font-bold block hover:text-gray-200">Perpustakaan Digital UTM</a>
            <nav class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-gray-300 mt-1">
                <a href="/" class="hover:text-white {{ request()->is('/') ? 'font-semibold text-white' : '' }}">Beranda</a>
                <a href="/buku" class="hover:text-white {{ request()->is('buku*') ? 'font-semibold text-white' : '' }}">Daftar Buku</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="hover:text-white {{ request()->routeIs('dashboard') ? 'font-semibold text-white' : '' }}">Dashboard</a>
                    @role('admin|petugas')
                        <a href="{{ route('peminjaman.index') }}" class="hover:text-white {{ request()->routeIs('peminjaman.index') ? 'font-semibold text-white' : '' }}">Peminjaman</a>
                        <a href="{{ route('laporan.index') }}" class="hover:text-white {{ request()->routeIs('laporan.index') ? 'font-semibold text-white' : '' }}">Laporan</a>
                    @endrole
                    @role('admin')
                        <a href="{{ route('admin.users.index') }}" class="hover:text-white {{ request()->routeIs('admin.users.*') ? 'font-semibold text-white' : '' }}">Kelola Pengguna</a>
                    @endrole
                    @role('siswa')
                        <a href="{{ route('peminjaman.riwayat', Auth::id()) }}" class="hover:text-white {{ request()->is('riwayat-peminjaman*') ? 'font-semibold text-white' : '' }}">Riwayat Pinjam</a>
                    @endrole
                @endauth
            </nav>
        </div>

        <div class="flex items-center space-x-3">
            @auth
                <span class="text-xs bg-gray-700 text-yellow-300 px-2.5 py-1 rounded font-medium">
                    Role: {{ ucfirst(Auth::user()->roles->pluck('name')->first() ?? 'User') }}
                </span>
                <a href="{{ route('profile.edit') }}" class="text-sm text-gray-200 hover:text-white underline">
                    Profil ({{ Auth::user()->name }})
                </a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-sm transition font-medium">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium transition">
                    Login
                </a>
                <a href="{{ route('register') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm font-medium transition">
                    Register
                </a>
            @endauth
        </div>
    </div>
</header>
