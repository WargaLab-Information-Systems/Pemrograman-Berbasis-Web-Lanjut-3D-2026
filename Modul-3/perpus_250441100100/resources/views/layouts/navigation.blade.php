<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    {{-- Menu Dashboard Sesuai Role --}}
                    @role('admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Dashboard Admin') }}
                        </x-nav-link>
                    @elserole('petugas')
                        <x-nav-link :href="route('petugas.dashboard')" :active="request()->routeIs('petugas.dashboard')">
                            {{ __('Dashboard Petugas') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('siswa.dashboard')" :active="request()->routeIs('siswa.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    @endrole

                    <!-- Daftar Buku -->
                    <x-nav-link :href="route('buku.index')" :active="request()->routeIs('buku.*')">
                        {{ __('Daftar Buku') }}
                    </x-nav-link>

                    <!-- Kelola Pengguna (Khusus Admin) -->
                    @can('kelola data pengguna')
                        <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                            {{ __('Kelola Pengguna') }}
                        </x-nav-link>
                    @endcan

                    <!-- Peminjaman & Pengembalian (Admin & Petugas) -->
                    @can('kelola peminjaman')
                        <x-nav-link :href="route('peminjaman.index')" :active="request()->routeIs('peminjaman.index')">
                            {{ __('Peminjaman') }}
                        </x-nav-link>
                    @endcan

                    <!-- Pengembalian (Admin & Petugas) -->
                    @can('kelola pengembalian')
                        <x-nav-link :href="route('pengembalian.index')" :active="request()->routeIs('pengembalian.index')">
                            {{ __('Pengembalian') }}
                        </x-nav-link>
                    @endcan

                    <!-- Status Peminjaman Saya -->
                    @can('melihat status peminjaman sendiri')
                        <x-nav-link :href="route('peminjaman.status.sendiri')" :active="request()->routeIs('peminjaman.status.sendiri')">
                            {{ __('Status Peminjaman') }}
                        </x-nav-link>
                    @endcan

                    <!-- Riwayat Saya (milik sendiri, semua role) -->
                    @can('melihat riwayat peminjaman sendiri')
                        <x-nav-link :href="route('peminjaman.riwayat.sendiri')" :active="request()->routeIs('peminjaman.riwayat.sendiri')">
                            {{ __('Riwayat Saya') }}
                        </x-nav-link>
                    @endcan

                    <!-- Laporan (Admin & Petugas) -->
                    @can('lihat laporan perpustakaan')
                        <x-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.index')">
                            {{ __('Laporan') }}
                        </x-nav-link>
                    @endcan
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profil.saya')">
                            {{ __('Profil Saya') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>