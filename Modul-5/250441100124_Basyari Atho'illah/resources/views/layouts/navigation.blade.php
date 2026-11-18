<nav x-data="{ open: false }" class="bg-white border-b border-slate-200 sticky top-0 z-40 backdrop-blur-md bg-white/95">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Brand / Logo -->
                <div class="shrink-0 flex items-center gap-2">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 font-bold text-indigo-600 text-lg hover:text-indigo-700 transition">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 text-white flex items-center justify-center shadow-sm">
                            <i class="fa-solid fa-book-bookmark text-base"></i>
                        </div>
                        <span class="tracking-tight text-slate-900 hidden sm:inline">Perpustakaan<span class="text-indigo-600">Digital</span></span>
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-8 md:flex items-center">
                    <!-- Home -->
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        <i class="fa-solid fa-house me-1.5 text-xs"></i> {{ __('Beranda') }}
                    </x-nav-link>

                    <!-- Katalog Buku (Semua Role & Tamu) -->
                    <x-nav-link :href="route('buku.index')" :active="request()->routeIs('buku.*')">
                        <i class="fa-solid fa-books me-1.5 text-xs"></i> {{ __('Katalog Buku') }}
                    </x-nav-link>

                    @auth
                        {{-- MENU KHUSUS ADMIN --}}
                        @role('admin')
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                <i class="fa-solid fa-gauge-high me-1.5 text-xs text-amber-500"></i> {{ __('Dashboard Admin') }}
                            </x-nav-link>

                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                <i class="fa-solid fa-users-gear me-1.5 text-xs text-indigo-500"></i> {{ __('Kelola User & Role') }}
                            </x-nav-link>
                        @endrole

                        {{-- MENU KHUSUS PETUGAS --}}
                        @role('petugas')
                            <x-nav-link :href="route('petugas.dashboard')" :active="request()->routeIs('petugas.dashboard')">
                                <i class="fa-solid fa-shield-halved me-1.5 text-xs text-sky-500"></i> {{ __('Dashboard Petugas') }}
                            </x-nav-link>
                        @endrole

                        {{-- MENU PETUGAS & ADMIN (Operasional Perpustakaan) --}}
                        @hasanyrole('admin|petugas')
                            <x-nav-link :href="route('peminjaman.index')" :active="request()->routeIs('peminjaman.index')">
                                <i class="fa-solid fa-clipboard-list me-1.5 text-xs text-teal-500"></i> {{ __('Peminjaman') }}
                            </x-nav-link>

                            <x-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.index')">
                                <i class="fa-solid fa-chart-pie me-1.5 text-xs text-violet-500"></i> {{ __('Laporan') }}
                            </x-nav-link>
                        @endhasanyrole

                        {{-- MENU KHUSUS SISWA --}}
                        @role('siswa')
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                <i class="fa-solid fa-grid-2 me-1.5 text-xs text-emerald-500"></i> {{ __('Dashboard') }}
                            </x-nav-link>

                            <x-nav-link :href="route('siswa.peminjaman.riwayat')" :active="request()->routeIs('siswa.peminjaman.riwayat')">
                                <i class="fa-solid fa-clock-rotate-left me-1.5 text-xs text-indigo-500"></i> {{ __('Riwayat Pinjam') }}
                            </x-nav-link>
                        @endrole
                    @endauth
                </div>
            </div>

            <!-- User Menu / Auth Buttons (Desktop) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <!-- Role Pill Badge -->
                    @php
                        $userRole = Auth::user()->roles->first()?->name ?? 'User';
                        $roleColors = [
                            'admin' => 'bg-amber-100 text-amber-800 border-amber-300',
                            'petugas' => 'bg-sky-100 text-sky-800 border-sky-300',
                            'siswa' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                        ];
                        $badgeColor = $roleColors[$userRole] ?? 'bg-slate-100 text-slate-800 border-slate-300';
                    @endphp
                    <span class="me-3 px-2.5 py-1 text-xs font-semibold uppercase tracking-wider rounded-full border {{ $badgeColor }}">
                        <i class="fa-solid fa-shield text-[10px] me-1"></i> {{ $userRole }}
                    </span>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-3 py-2 border border-slate-200 text-sm font-medium rounded-xl text-slate-700 bg-white hover:bg-slate-50 hover:text-slate-900 focus:outline-none transition shadow-2xs">
                                <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-xs">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="max-w-[120px] truncate text-left">{{ Auth::user()->name }}</span>
                                <i class="fa-solid fa-chevron-down text-slate-400 text-xs"></i>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-2 border-b border-slate-100 text-xs text-slate-500">
                                Signed in as <strong class="text-slate-800">{{ Auth::user()->email }}</strong>
                            </div>

                            <x-dropdown-link :href="route('profile.edit')">
                                <i class="fa-solid fa-user-circle me-2 text-slate-400"></i> {{ __('Profil Saya') }}
                            </x-dropdown-link>

                            @role('siswa')
                                <x-dropdown-link :href="route('siswa.peminjaman.riwayat')">
                                    <i class="fa-solid fa-clock-rotate-left me-2 text-slate-400"></i> {{ __('Pinjaman Saya') }}
                                </x-dropdown-link>
                            @endrole

                            <!-- Authentication Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        class="text-rose-600 hover:text-rose-700 hover:bg-rose-50"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="fa-solid fa-arrow-right-from-bracket me-2 text-rose-500"></i> {{ __('Keluar (Logout)') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 px-3 py-2 rounded-lg transition">
                            <i class="fa-solid fa-right-to-bracket me-1.5"></i> Masuk
                        </a>
                        <a href="{{ route('register') }}" class="text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-xl shadow-xs transition">
                            <i class="fa-solid fa-user-plus me-1.5"></i> Daftar Akun
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger Button (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-slate-200 bg-white">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                <i class="fa-solid fa-house me-2"></i> {{ __('Beranda') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('buku.index')" :active="request()->routeIs('buku.*')">
                <i class="fa-solid fa-books me-2"></i> {{ __('Katalog Buku') }}
            </x-responsive-nav-link>

            @auth
                @role('admin')
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        <i class="fa-solid fa-gauge-high me-2 text-amber-500"></i> {{ __('Dashboard Admin') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        <i class="fa-solid fa-users-gear me-2 text-indigo-500"></i> {{ __('Kelola User & Role') }}
                    </x-responsive-nav-link>
                @endrole

                @role('petugas')
                    <x-responsive-nav-link :href="route('petugas.dashboard')" :active="request()->routeIs('petugas.dashboard')">
                        <i class="fa-solid fa-shield-halved me-2 text-sky-500"></i> {{ __('Dashboard Petugas') }}
                    </x-responsive-nav-link>
                @endrole

                @hasanyrole('admin|petugas')
                    <x-responsive-nav-link :href="route('peminjaman.index')" :active="request()->routeIs('peminjaman.index')">
                        <i class="fa-solid fa-clipboard-list me-2 text-teal-500"></i> {{ __('Peminjaman') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.index')">
                        <i class="fa-solid fa-chart-pie me-2 text-violet-500"></i> {{ __('Laporan') }}
                    </x-responsive-nav-link>
                @endhasanyrole

                @role('siswa')
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <i class="fa-solid fa-grid-2 me-2 text-emerald-500"></i> {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('siswa.peminjaman.riwayat')" :active="request()->routeIs('siswa.peminjaman.riwayat')">
                        <i class="fa-solid fa-clock-rotate-left me-2 text-indigo-500"></i> {{ __('Riwayat Pinjam') }}
                    </x-responsive-nav-link>
                @endrole
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t border-slate-200">
            @auth
                <div class="px-4 flex items-center justify-between">
                    <div>
                        <div class="font-medium text-base text-slate-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-slate-500">{{ Auth::user()->email }}</div>
                    </div>
                    <span class="px-2.5 py-1 text-xs font-semibold uppercase tracking-wider rounded-full border {{ $badgeColor }}">
                        {{ $userRole }}
                    </span>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        <i class="fa-solid fa-user-circle me-2"></i> {{ __('Profil Saya') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                class="text-rose-600"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> {{ __('Keluar (Logout)') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="px-4 py-2 space-y-2">
                    <a href="{{ route('login') }}" class="block text-center w-full py-2.5 px-4 text-sm font-semibold rounded-xl text-slate-700 bg-slate-100 hover:bg-slate-200">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="block text-center w-full py-2.5 px-4 text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700">
                        Daftar Akun
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>
