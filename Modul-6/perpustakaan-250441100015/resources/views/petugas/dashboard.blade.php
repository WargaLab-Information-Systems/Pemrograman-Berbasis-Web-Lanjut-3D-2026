
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <span class="text-xl">📚</span>
            <h2 class="font-bold text-xl text-gray-800">Dashboard Petugas</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Welcome Banner -->
            <div class="rounded-2xl p-8 text-white shadow-xl relative overflow-hidden"
                 style="background: linear-gradient(135deg, #0f766e 0%, #0d9488 50%, #0891b2 100%);"
                 data-aos="fade-down" data-aos-duration="700">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full translate-x-1/3 -translate-y-1/3"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -translate-x-1/3 translate-y-1/3"></div>
                <div class="relative z-10">
                    <p class="text-teal-200 text-sm font-medium mb-1">Selamat datang,</p>
                    <h1 class="text-3xl font-bold mb-2">{{ Auth::user()->name }} 📋</h1>
                    <p class="text-teal-100 text-sm">
                        Anda masuk sebagai <strong class="text-white">Petugas Perpustakaan</strong>.
                        Kelola peminjaman buku dan daftar koleksi perpustakaan.
                    </p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6" data-aos="fade-up" data-aos-duration="700" data-aos-delay="100">

                <!-- Manajemen Peminjaman -->
                <a href="{{ route('peminjaman.index') }}"
                   class="bg-white rounded-2xl p-8 shadow-md border border-emerald-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center text-3xl mb-5 group-hover:bg-emerald-200 transition-colors">
                        📋
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Manajemen Peminjaman</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Lihat dan kelola seluruh pengajuan peminjaman buku dari siswa. Setujui, tolak, atau catat pengembalian buku.
                    </p>
                    <div class="mt-5 flex items-center text-emerald-600 text-sm font-semibold">
                        Buka halaman
                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>

                <!-- Daftar Buku -->
                <a href="{{ route('buku.index') }}"
                   class="bg-white rounded-2xl p-8 shadow-md border border-blue-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-3xl mb-5 group-hover:bg-blue-200 transition-colors">
                        📖
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Daftar Buku</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Jelajahi koleksi buku yang tersedia di perpustakaan. Tambah, edit, atau hapus data buku sesuai kebutuhan.
                    </p>
                    <div class="mt-5 flex items-center text-blue-600 text-sm font-semibold">
                        Buka halaman
                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            </div>

            <!-- Info card -->
            <div class="bg-teal-50 border border-teal-200 rounded-2xl p-6" data-aos="fade-up" data-aos-duration="700" data-aos-delay="200">
                <div class="flex items-start gap-4">
                    <span class="text-2xl">💡</span>
                    <div>
                        <h4 class="font-semibold text-teal-800 mb-1">Tips Petugas</h4>
                        <p class="text-teal-700 text-sm leading-relaxed">
                            Pastikan untuk memverifikasi identitas anggota sebelum menyetujui pengajuan peminjaman.
                            Catat pengembalian buku tepat waktu untuk menjaga akurasi data perpustakaan.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
