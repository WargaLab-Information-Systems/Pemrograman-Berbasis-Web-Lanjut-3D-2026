<nav x-data="{ open: false }" class="bg-white border-b border-slate-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-6">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-bold text-slate-900 text-lg tracking-tight">
                        <span>Amba<span class="text-blue-600">Pus</span></span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-6 sm:flex items-center">

                    @can('Akses dashboard petugas')
                    <x-nav-link :href="route('petugas.dashboard')" :active="request()->routeIs('petugas.dashboard')" class="rounded-lg px-3 py-2 text-sm font-semibold transition">
                        Dashboard Petugas
                    </x-nav-link>
                    @endcan

                    @can('Lihat daftar buku')
                    <x-nav-link :href="route('buku.index')" :active="request()->routeIs('buku.index')" class="rounded-lg px-3 py-2 text-sm font-semibold transition">
                        Katalog Buku
                    </x-nav-link>
                    @endcan

                    @can('Lihat riwayat peminjaman')
                    <x-nav-link :href="route('peminjaman.riwayat')" :active="request()->routeIs('peminjaman.riwayat')" class="rounded-lg px-3 py-2 text-sm font-semibold transition">
                        Riwayat Peminjaman
                    </x-nav-link>
                    @endcan

                    @can('Kelola peminjaman')
                    <x-nav-link :href="route('peminjaman.index')" :active="request()->routeIs('peminjaman.index')" class="rounded-lg px-3 py-2 text-sm font-semibold transition">
                        Kelola Peminjaman
                    </x-nav-link>
                    @endcan

                    @can('Lihat laporan')
                    <x-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.index')" class="rounded-lg px-3 py-2 text-sm font-semibold transition">
                        Laporan
                    </x-nav-link>
                    @endcan

                    @can('Akses dashboard admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="rounded-lg px-3 py-2 text-sm font-semibold transition">
                            Dashboard Admin
                        </x-nav-link>

                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')" class="rounded-lg px-3 py-2 text-sm font-semibold transition">
                            Kelola Role
                        </x-nav-link>
                    @endcan
                </div>
            </div>

            <!-- Profile & Role Badge -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="mr-3">
                    @role('admin')
                        <span class="px-2.5 py-1 rounded text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">Admin</span>
                    @endrole
                    @role('petugas')
                        <span class="px-2.5 py-1 rounded text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Petugas</span>
                    @endrole
                    @role('siswa')
                        <span class="px-2.5 py-1 rounded text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">Siswa</span>
                    @endrole
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-1.5 border border-slate-200 text-sm font-medium rounded-lg text-slate-700 bg-slate-50 hover:bg-slate-100 focus:outline-none transition">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Edit Profil
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    class="text-rose-600 font-semibold">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>