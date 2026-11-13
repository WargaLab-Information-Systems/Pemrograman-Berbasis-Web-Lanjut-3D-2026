
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <span class="text-xl">🎓</span>
            <h2 class="font-bold text-xl text-gray-800">Dashboard Siswa</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Welcome Banner -->
            <div class="rounded-2xl p-8 text-white shadow-xl relative overflow-hidden"
                 style="background: linear-gradient(135deg, #7c3aed 0%, #8b5cf6 50%, #a78bfa 100%);"
                 data-aos="fade-down" data-aos-duration="700">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full translate-x-1/3 -translate-y-1/3"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -translate-x-1/3 translate-y-1/3"></div>
                <div class="relative z-10">
                    <p class="text-purple-200 text-sm font-medium mb-1">Halo,</p>
                    <h1 class="text-3xl font-bold mb-2">{{ Auth::user()->name }} 🎓</h1>
                    <p class="text-purple-100 text-sm">
                        Selamat datang di portal siswa. Jelajahi koleksi buku dan kelola riwayat peminjaman Anda.
                    </p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6" data-aos="fade-up" data-aos-duration="700" data-aos-delay="100">

                <!-- Daftar Buku -->
                <a href="{{ route('buku.index') }}"
                   class="bg-white rounded-2xl p-8 shadow-md border border-purple-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center text-3xl mb-5 group-hover:bg-purple-200 transition-colors">
                        📚
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Daftar Buku</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Jelajahi ribuan koleksi buku yang tersedia di perpustakaan dan ajukan peminjaman buku favorit Anda.
                    </p>
                    <div class="mt-5 flex items-center text-purple-600 text-sm font-semibold">
                        Jelajahi koleksi
                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>

                <!-- Riwayat Peminjaman -->
                <a href="{{ route('siswa.riwayat-peminjaman') }}"
                   class="bg-white rounded-2xl p-8 shadow-md border border-indigo-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center text-3xl mb-5 group-hover:bg-indigo-200 transition-colors">
                        🕐
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Riwayat Peminjaman</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Lihat seluruh riwayat peminjaman buku Anda, termasuk status pengajuan dan tanggal pengembalian.
                    </p>
                    <div class="mt-5 flex items-center text-indigo-600 text-sm font-semibold">
                        Lihat riwayat
                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            </div>

            <!-- Info / reminder card -->
            <div class="bg-purple-50 border border-purple-200 rounded-2xl p-6" data-aos="fade-up" data-aos-duration="700" data-aos-delay="200">
                <div class="flex items-start gap-4">
                    <span class="text-2xl">📌</span>
                    <div>
                        <h4 class="font-semibold text-purple-800 mb-1">Peraturan Peminjaman</h4>
                        <ul class="text-purple-700 text-sm space-y-1 leading-relaxed list-disc list-inside">
                            <li>Buku yang dipinjam harus dikembalikan tepat waktu.</li>
                            <li>Jaga kondisi buku agar tetap baik selama masa pinjam.</li>
                            <li>Hubungi petugas jika ada kendala dalam proses pengembalian.</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
