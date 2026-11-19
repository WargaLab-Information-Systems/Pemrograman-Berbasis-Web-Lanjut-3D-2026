<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center font-bold text-rose-700 text-lg">
                    <a href="{{ route('home') }}">
                        Perpustakaan Online
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        Beranda
                    </x-nav-link>

                    <x-nav-link :href="route('buku.index')" :active="request()->routeIs('buku.*')">
                        Daftar Buku
                    </x-nav-link>

                    @auth
                        @role('admin')
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                Dashboard Admin
                            </x-nav-link>
                            <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                                Kelola Pengguna
                            </x-nav-link>
                            <x-nav-link :href="route('admin.peminjaman')" :active="request()->routeIs('admin.peminjaman')">
                                Data Peminjaman
                            </x-nav-link>
                            <x-nav-link :href="route('admin.laporan')" :active="request()->routeIs('admin.laporan')">
                                Laporan
                            </x-nav-link>
                        @endrole

                        @role('petugas')
                            <x-nav-link :href="route('petugas.dashboard')" :active="request()->routeIs('petugas.dashboard')">
                                Dashboard Petugas
                            </x-nav-link>
                            <x-nav-link :href="route('petugas.peminjaman')" :active="request()->routeIs('petugas.peminjaman')">
                                Proses Peminjaman
                            </x-nav-link>
                        @endrole

                        @role('siswa')
                            <x-nav-link :href="route('siswa.riwayat')" :active="request()->routeIs('siswa.riwayat')">
                                Riwayat Peminjaman
                            </x-nav-link>
                        @endrole
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-gray-900 focus:outline-none transition ease-in-out duration-150">
                                <div>
                                    <span class="font-semibold">{{ Auth::user()->name }}</span>
                                    <span class="text-xs bg-rose-100 text-rose-800 px-2 py-0.5 rounded-full ms-1 capitalize">
                                        {{ Auth::user()->roles->pluck('name')->first() ?? 'Pengguna' }}
                                    </span>
                                </div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                Profil Saya
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    Keluar (Logout)
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="space-x-3">
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-rose-700 hover:underline">Masuk</a>
                        <a href="{{ route('register') }}" class="text-sm bg-rose-600 text-white px-3 py-1.5 rounded hover:bg-rose-700">Daftar</a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')">
                Beranda
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('buku.index')">
                Daftar Buku
            </x-responsive-nav-link>

            @auth
                @role('admin')
                    <x-responsive-nav-link :href="route('admin.dashboard')">
                        Dashboard Admin
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.users')">
                        Kelola Pengguna
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.peminjaman')">
                        Data Peminjaman
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.laporan')">
                        Laporan
                    </x-responsive-nav-link>
                @endrole

                @role('petugas')
                    <x-responsive-nav-link :href="route('petugas.dashboard')">
                        Dashboard Petugas
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('petugas.peminjaman')">
                        Proses Peminjaman
                    </x-responsive-nav-link>
                @endrole

                @role('siswa')
                    <x-responsive-nav-link :href="route('siswa.riwayat')">
                        Riwayat Peminjaman
                    </x-responsive-nav-link>
                @endrole
            @endauth
        </div>

        @auth
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        Profil Saya
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            Keluar (Logout)
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-3 border-t border-gray-200 px-4 space-y-2">
                <a href="{{ route('login') }}" class="block text-sm font-semibold text-rose-700">Masuk</a>
                <a href="{{ route('register') }}" class="block text-sm font-semibold text-rose-700">Daftar</a>
            </div>
        @endauth
    </div>
</nav>
