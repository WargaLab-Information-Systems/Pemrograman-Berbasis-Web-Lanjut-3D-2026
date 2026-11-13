<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <span class="text-xl">📊</span>
            <h2 class="font-bold text-xl text-gray-800">Laporan Perpustakaan</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Page Header Banner -->
            <div class="rounded-2xl p-7 text-white shadow-xl relative overflow-hidden"
                 style="background: linear-gradient(135deg, #1e40af 0%, #3B82F6 50%, #0891b2 100%);"
                 data-aos="fade-down" data-aos-duration="700">
                <div class="absolute top-0 right-0 w-56 h-56 bg-white/5 rounded-full translate-x-1/3 -translate-y-1/3"></div>
                <div class="relative z-10">
                    <p class="text-blue-200 text-xs font-bold uppercase tracking-widest mb-2">STATISTIK SISTEM</p>
                    <h2 class="text-2xl font-bold mb-1">Laporan Perpustakaan</h2>
                    <p class="text-blue-100 text-sm">Ringkasan data dan statistik perpustakaan secara keseluruhan.</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5" data-aos="fade-up" data-aos-duration="700" data-aos-delay="100">

                <!-- Total Buku -->
                <div class="bg-white rounded-2xl p-6 shadow-md border border-indigo-50 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-2xl">📚</div>
                        <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full">Koleksi</span>
                    </div>
                    <p class="text-3xl font-bold text-gray-800 mb-1">{{ $totalBuku }}</p>
                    <p class="text-sm text-gray-500 font-medium">Total Buku</p>
                </div>

                <!-- Total Kategori -->
                <div class="bg-white rounded-2xl p-6 shadow-md border border-blue-50 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-2xl">🏷️</div>
                        <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">Kategori</span>
                    </div>
                    <p class="text-3xl font-bold text-gray-800 mb-1">{{ $totalKategori }}</p>
                    <p class="text-sm text-gray-500 font-medium">Total Kategori</p>
                </div>

                <!-- Total Anggota -->
                <div class="bg-white rounded-2xl p-6 shadow-md border border-emerald-50 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-2xl">👥</div>
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">Anggota</span>
                    </div>
                    <p class="text-3xl font-bold text-gray-800 mb-1">{{ $totalAnggota }}</p>
                    <p class="text-sm text-gray-500 font-medium">Total Anggota</p>
                </div>

                <!-- Total Peminjaman -->
                <div class="bg-white rounded-2xl p-6 shadow-md border border-purple-50 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-2xl">📋</div>
                        <span class="text-xs font-bold text-purple-600 bg-purple-50 px-2.5 py-1 rounded-full">Transaksi</span>
                    </div>
                    <p class="text-3xl font-bold text-gray-800 mb-1">{{ $totalPeminjaman }}</p>
                    <p class="text-sm text-gray-500 font-medium">Total Peminjaman</p>
                </div>
            </div>

            <!-- Recap Table -->
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden" data-aos="fade-up" data-aos-duration="700" data-aos-delay="200">

                <div class="px-6 py-5 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <span>📑</span> Rekapitulasi Status Peminjaman
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Ringkasan jumlah transaksi berdasarkan status peminjaman.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="bg-indigo-50">
                                <th class="px-6 py-4 text-xs font-bold text-indigo-700 uppercase tracking-wider w-12">No.</th>
                                <th class="px-6 py-4 text-xs font-bold text-indigo-700 uppercase tracking-wider">Status Peminjaman</th>
                                <th class="px-6 py-4 text-xs font-bold text-indigo-700 uppercase tracking-wider">Badge</th>
                                <th class="px-6 py-4 text-xs font-bold text-indigo-700 uppercase tracking-wider text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <!-- Menunggu -->
                            <tr class="hover:bg-yellow-50/50 transition-colors">
                                <td class="px-6 py-4 text-gray-500">1</td>
                                <td class="px-6 py-4 font-medium text-gray-800">Menunggu</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        ⏳ Menunggu
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-lg font-bold text-yellow-700">{{ $peminjamanMenunggu }}</span>
                                </td>
                            </tr>

                            <!-- Disetujui -->
                            <tr class="hover:bg-emerald-50/50 transition-colors">
                                <td class="px-6 py-4 text-gray-500">2</td>
                                <td class="px-6 py-4 font-medium text-gray-800">Disetujui</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        ✅ Disetujui
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-lg font-bold text-emerald-700">{{ $peminjamanDisetujui }}</span>
                                </td>
                            </tr>

                            <!-- Ditolak -->
                            <tr class="hover:bg-red-50/50 transition-colors">
                                <td class="px-6 py-4 text-gray-500">3</td>
                                <td class="px-6 py-4 font-medium text-gray-800">Ditolak</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                        ❌ Ditolak
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-lg font-bold text-red-700">{{ $peminjamanDitolak }}</span>
                                </td>
                            </tr>

                            <!-- Dikembalikan -->
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-6 py-4 text-gray-500">4</td>
                                <td class="px-6 py-4 font-medium text-gray-800">Dikembalikan</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                        📦 Dikembalikan
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-lg font-bold text-blue-700">{{ $peminjamanDikembalikan }}</span>
                                </td>
                            </tr>
                        </tbody>

                        <!-- Total row -->
                        <tfoot>
                            <tr class="bg-indigo-50">
                                <td colspan="3" class="px-6 py-4 font-bold text-indigo-700 uppercase text-xs tracking-wider">
                                    Total Keseluruhan
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-xl font-extrabold text-indigo-700">{{ $totalPeminjaman }}</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
