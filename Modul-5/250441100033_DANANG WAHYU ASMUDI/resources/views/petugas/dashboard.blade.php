<x-app-layout>
    <div class="bg-white p-6 rounded-lg shadow-md max-w-5xl mx-auto space-y-6">
        <div class="border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Petugas Perpustakaan</h1>
            <p class="text-gray-600 mt-1 text-sm">Selamat datang, <span class="font-semibold text-gray-800">{{ Auth::user()->name }}</span>. Anda masuk sebagai <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded text-xs font-semibold">Petugas</span>.</p>
        </div>

        <div>
            <h2 class="text-lg font-bold text-gray-800 mb-3">Menu Operasional Petugas</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Kelola Data Buku -->
                <a href="{{ route('buku.index') }}" class="p-4 bg-gray-50 border rounded-lg hover:bg-gray-100 transition block">
                    <div class="font-bold text-gray-800 mb-1">Kelola Data Buku</div>
                    <p class="text-xs text-gray-500">Katalog buku, tambah, edit, atau hapus buku.</p>
                </a>

                <!-- Peminjaman & Pengembalian -->
                <a href="{{ route('peminjaman.index') }}" class="p-4 bg-green-50 border-2 border-green-200 rounded-lg hover:bg-green-100 transition block">
                    <div class="font-bold text-green-900 mb-1">Peminjaman & Pengembalian</div>
                    <p class="text-xs text-gray-600">Validasi persetujuan pinjam dan catat pengembalian.</p>
                </a>

                <!-- Laporan Perpustakaan -->
                <a href="{{ route('laporan.index') }}" class="p-4 bg-gray-50 border rounded-lg hover:bg-gray-100 transition block">
                    <div class="font-bold text-gray-800 mb-1">Laporan Perpustakaan</div>
                    <p class="text-xs text-gray-500">Lihat rekap buku per kategori dan data peminjaman.</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
