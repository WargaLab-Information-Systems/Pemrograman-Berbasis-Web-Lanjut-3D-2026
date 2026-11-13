<nav x-data="{ open: false }" style="background: linear-gradient(135deg, #3730a3 0%, #4F46E5 50%, #2563EB 100%);" class="shadow-xl">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo / Branding -->
                <div class="shrink-0 flex items-center mr-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                        <span class="text-2xl">📚</span>
                        <span class="text-white font-bold text-lg tracking-tight group-hover:text-indigo-200 transition-colors">
                            Perpustakaan
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:flex">
                    @role('admin')
                        <a href="{{ route('admin.dashboard') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">
                            🏠 Dashboard
                        </a>
                    @endrole
                    @role('petugas')
                        <a href="{{ route('petugas.dashboard') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('petugas.dashboard') ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">
                            🏠 Dashboard
                        </a>
                    @endrole
                    @role('siswa')
                        <a href="{{ route('siswa.dashboard') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('siswa.dashboard') ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">
                            🏠 Dashboard
                        </a>
                    @endrole

                    @can('view books')
                        <a href="{{ route('buku.index') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('buku.*') ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">
                            📖 Daftar Buku
                        </a>
                    @endcan

                    @can('manage users')
                        <a href="{{ route('admin.users.index') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('admin.users.*') ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">
                            👥 Pengguna
                        </a>
                    @endcan

                    @can('manage loans')
                        <a href="{{ route('peminjaman.index') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('peminjaman.*') ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">
                            📋 Peminjaman
                        </a>
                    @endcan

                    @can('view library reports')
                        <a href="{{ route('laporan-perpustakaan.index') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('laporan-perpustakaan.*') ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">
                            📊 Laporan
                        </a>
                    @endcan

                    @role('siswa')
                        <a href="{{ route('siswa.riwayat-peminjaman') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('siswa.riwayat-peminjaman') ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">
                            🕐 Riwayat Pinjam
                        </a>
                    @endrole
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center gap-3">
                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-indigo-100 hover:bg-white/10 hover:text-white transition-all duration-200 focus:outline-none">
                        <!-- Avatar circle -->
                        <span class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </span>
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': dropdownOpen }"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="dropdownOpen"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         @click.outside="dropdownOpen = false"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden"
                         style="display: none;">
                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profil Saya
                        </a>
                        <div class="border-t border-gray-100"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="flex items-center gap-2 w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors text-left">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-indigo-200 hover:text-white hover:bg-white/10 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden border-t border-white/10">
        <div class="pt-2 pb-3 space-y-1 px-4">
            @role('admin')
                <a href="{{ route('admin.dashboard') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium text-indigo-100 hover:bg-white/10 hover:text-white transition-all">
                    🏠 Dashboard Admin
                </a>
            @endrole
            @role('petugas')
                <a href="{{ route('petugas.dashboard') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium text-indigo-100 hover:bg-white/10 hover:text-white transition-all">
                    🏠 Dashboard Petugas
                </a>
            @endrole
            @role('siswa')
                <a href="{{ route('siswa.dashboard') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium text-indigo-100 hover:bg-white/10 hover:text-white transition-all">
                    🏠 Dashboard Siswa
                </a>
            @endrole
            @can('view books')
                <a href="{{ route('buku.index') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium text-indigo-100 hover:bg-white/10 hover:text-white transition-all">
                    📖 Daftar Buku
                </a>
            @endcan
            @can('manage users')
                <a href="{{ route('admin.users.index') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium text-indigo-100 hover:bg-white/10 hover:text-white transition-all">
                    👥 Manajemen Pengguna
                </a>
            @endcan
            @can('manage loans')
                <a href="{{ route('peminjaman.index') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium text-indigo-100 hover:bg-white/10 hover:text-white transition-all">
                    📋 Manajemen Peminjaman
                </a>
            @endcan
            @can('view library reports')
                <a href="{{ route('laporan-perpustakaan.index') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium text-indigo-100 hover:bg-white/10 hover:text-white transition-all">
                    📊 Laporan Perpustakaan
                </a>
            @endcan
            @role('siswa')
                <a href="{{ route('siswa.riwayat-peminjaman') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium text-indigo-100 hover:bg-white/10 hover:text-white transition-all">
                    🕐 Riwayat Peminjaman
                </a>
            @endrole
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-3 pb-3 border-t border-white/10 px-4">
            <div class="flex items-center gap-3 px-3 py-2">
                <span class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>
                <div>
                    <div class="font-semibold text-white text-sm">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-indigo-200">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-2 space-y-1">
                <a href="{{ route('profile.edit') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium text-indigo-100 hover:bg-white/10 hover:text-white transition-all">
                    Profil Saya
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="block w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-red-300 hover:bg-white/10 hover:text-red-200 transition-all">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
