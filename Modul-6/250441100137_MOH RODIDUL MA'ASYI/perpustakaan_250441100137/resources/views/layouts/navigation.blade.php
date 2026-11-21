<nav x-data="{ open: false }" class="bg-white border-b border-[#ebe5df] shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center space-x-6 lg:space-x-8">
                <!-- Logo & Brand (Senada dengan tema tabel cokelat gelap & amber) -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="font-bold text-lg lg:text-xl text-[#342a26] hover:text-[#bc6c25] flex items-center gap-2.5 transition">
                        <svg class="w-7 h-7 text-[#bc6c25]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span class="tracking-tight font-extrabold">Perpustakaan Digital</span>
                    </a>
                </div>

                <!-- Navigation Links (Menggunakan styling senada tanpa text-wrap) -->
                <div class="hidden md:flex space-x-4 lg:space-x-6 items-center h-16">
                    {{-- Dashboard Menu --}}
                    @can('Akses dashboard admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Dashboard Admin') }}
                        </x-nav-link>
                    @endcan

                    @can('Akses dashboard petugas')
                        <x-nav-link :href="route('petugas.dashboard')" :active="request()->routeIs('petugas.dashboard')">
                            {{ __('Dashboard Petugas') }}
                        </x-nav-link>
                    @endcan

                    @if(!Auth::user()->can('Akses dashboard admin') && !Auth::user()->can('Akses dashboard petugas'))
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard Siswa') }}
                        </x-nav-link>
                    @endif

                    {{-- Katalog Buku (Semua Role) --}}
                    @can('Lihat daftar buku')
                        <x-nav-link :href="route('buku.index')" :active="request()->routeIs('buku.index')">
                            {{ __('Katalog Buku') }}
                        </x-nav-link>
                    @endcan

                    {{-- Kelola Buku (Admin & Petugas) --}}
                    @can('Kelola data buku')
                        <x-nav-link :href="route('buku.kelola')" :active="request()->routeIs('buku.kelola', 'buku.create', 'buku.edit')">
                            {{ __('Kelola Buku') }}
                        </x-nav-link>
                    @endcan

                    {{-- Kelola Peminjaman (Admin & Petugas) --}}
                    @can('Kelola peminjaman')
                        <x-nav-link :href="route('peminjaman.index')" :active="request()->routeIs('peminjaman.index')">
                            {{ __('Kelola Peminjaman') }}
                        </x-nav-link>
                    @endcan

                    {{-- Riwayat Peminjaman (Siswa / Semua Role) --}}
                    @can('Melihat riwayat peminjaman sendiri')
                        <x-nav-link :href="route('peminjaman.riwayat')" :active="request()->routeIs('peminjaman.riwayat')">
                            {{ __('Riwayat Peminjaman') }}
                        </x-nav-link>
                    @endcan

                    {{-- Kelola Pengguna & Role (Admin Saja) --}}
                    @can('Kelola data pengguna')
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                            {{ __('Pengguna & Role') }}
                        </x-nav-link>
                    @endcan

                    {{-- Laporan Perpustakaan (Admin & Petugas) --}}
                    @can('Lihat laporan perpustakaan')
                        <x-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.index')">
                            {{ __('Laporan') }}
                        </x-nav-link>
                    @endcan
                </div>
            </div>

            <!-- Settings Dropdown & User Role Badge -->
            <div class="hidden md:flex items-center space-x-3">
                @php
                    $roleName = Auth::user()->getRoleNames()->first() ?? Auth::user()->role;
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider
                    @if($roleName === 'admin') bg-[#fcece8] text-[#a12c1b] border border-[#f3c9c0]
                    @elseif($roleName === 'petugas') bg-[#fef3e7] text-[#bc6c25] border border-[#f6d5b8]
                    @else bg-[#eef7f2] text-[#2a7f5f] border border-[#c9ebd8] @endif">
                    {{ $roleName }}
                </span>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-1.5 border border-[#dcd5ce] text-sm leading-5 font-semibold rounded-md text-[#342a26] bg-white hover:bg-[#faf8f5] focus:outline-none transition">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1.5 text-[#6c5e5b]">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @can('Lihat profil sendiri')
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profil Saya') }}
                            </x-dropdown-link>
                        @endcan

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Keluar (Log Out)') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger for Mobile -->
            <div class="-me-2 flex items-center md:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-[#6c5e5b] hover:text-[#342a26] hover:bg-[#f5ede5] focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden bg-white border-t border-[#ebe5df]">
        <div class="pt-2 pb-3 space-y-1">
            @can('Akses dashboard admin')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard Admin') }}
                </x-responsive-nav-link>
            @endcan

            @can('Akses dashboard petugas')
                <x-responsive-nav-link :href="route('petugas.dashboard')" :active="request()->routeIs('petugas.dashboard')">
                    {{ __('Dashboard Petugas') }}
                </x-responsive-nav-link>
            @endcan

            @if(!Auth::user()->can('Akses dashboard admin') && !Auth::user()->can('Akses dashboard petugas'))
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard Siswa') }}
                </x-responsive-nav-link>
            @endif

            @can('Lihat daftar buku')
                <x-responsive-nav-link :href="route('buku.index')" :active="request()->routeIs('buku.index')">
                    {{ __('Katalog Buku') }}
                </x-responsive-nav-link>
            @endcan

            @can('Kelola data buku')
                <x-responsive-nav-link :href="route('buku.kelola')" :active="request()->routeIs('buku.kelola')">
                    {{ __('Kelola Buku') }}
                </x-responsive-nav-link>
            @endcan

            @can('Kelola peminjaman')
                <x-responsive-nav-link :href="route('peminjaman.index')" :active="request()->routeIs('peminjaman.index')">
                    {{ __('Kelola Peminjaman') }}
                </x-responsive-nav-link>
            @endcan

            @can('Melihat riwayat peminjaman sendiri')
                <x-responsive-nav-link :href="route('peminjaman.riwayat')" :active="request()->routeIs('peminjaman.riwayat')">
                    {{ __('Riwayat Peminjaman') }}
                </x-responsive-nav-link>
            @endcan

            @can('Kelola data pengguna')
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                    {{ __('Pengguna & Role') }}
                </x-responsive-nav-link>
            @endcan

            @can('Lihat laporan perpustakaan')
                <x-responsive-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.index')">
                    {{ __('Laporan') }}
                </x-responsive-nav-link>
            @endcan
        </div>

        <div class="pt-4 pb-3 border-t border-[#ebe5df]">
            <div class="px-4 mb-2">
                <div class="font-bold text-base text-[#342a26]">{{ Auth::user()->name }}</div>
                <div class="font-medium text-xs text-[#8c7e7b]">{{ Auth::user()->email }} ({{ strtoupper($roleName) }})</div>
            </div>

            <div class="mt-2 space-y-1">
                @can('Lihat profil sendiri')
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profil Saya') }}
                    </x-responsive-nav-link>
                @endcan

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Keluar (Log Out)') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
