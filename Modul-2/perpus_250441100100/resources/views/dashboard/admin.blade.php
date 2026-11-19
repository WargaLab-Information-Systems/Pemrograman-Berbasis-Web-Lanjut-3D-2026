<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen flex flex-col justify-between">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            
            <!-- Banner Header -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Dashboard Administrator</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Selamat datang, <span class="font-semibold text-gray-700">{{ Auth::user()->name }}</span>. Anda memiliki akses penuh ke seluruh administrasi perpustakaan.
                </p>
            </div>

            <!-- Grid 3 Kartu (Total Buku, Total User, Menu Cepat) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Kartu Total Koleksi Buku (Biru) -->
                <div class="bg-blue-600 text-white p-6 rounded-2xl shadow-sm flex flex-col justify-between h-40">
                    <span class="text-sm font-medium opacity-90">Total Koleksi Buku</span>
                    <span class="text-5xl font-extrabold">{{ $totalBuku }}</span>
                </div>

                <!-- Kartu Total Pengguna Terdaftar (Hijau) -->
                <div class="bg-emerald-600 text-white p-6 rounded-2xl shadow-sm flex flex-col justify-between h-40">
                    <span class="text-sm font-medium opacity-90">Total Pengguna Terdaftar</span>
                    <span class="text-5xl font-extrabold">{{ $totalUser }}</span>
                </div>

                <!-- Kartu Menu Cepat (Gelap) -->
                <div class="bg-slate-800 text-white p-6 rounded-2xl shadow-sm flex flex-col justify-between h-40">
                    <span class="text-sm font-medium text-gray-200">Menu Cepat</span>
                    <div class="flex items-center space-x-3 mt-4">
                        <a href="{{ route('buku.index') }}" class="bg-slate-100 hover:bg-white text-gray-900 font-semibold text-xs px-4 py-2.5 rounded-lg transition shadow-sm">
                            Kelola Buku
                        </a>
                        <a href="{{ route('admin.users') }}" class="bg-amber-500 hover:bg-amber-600 text-slate-900 font-semibold text-xs px-4 py-2.5 rounded-lg transition shadow-sm">
                            Kelola Role User
                        </a>
                        <a href="{{ route('peminjaman.index') }}" class="bg-slate-100 hover:bg-white text-gray-900 font-semibold text-xs px-4 py-2.5 rounded-lg transition shadow-sm">
                            Peminjaman
                        </a>
                        <a href="{{ route('laporan.index') }}" class="bg-slate-100 hover:bg-white text-gray-900 font-semibold text-xs px-4 py-2.5 rounded-lg transition shadow-sm">
                            Laporan
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <!-- Footer -->
        <footer class="py-6 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} Perpustakaan_250441100019
        </footer>
    </div>
</x-app-layout>