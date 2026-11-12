<x-app-layout>
    <div class="bg-white p-6 rounded-lg shadow-md max-w-5xl mx-auto space-y-6">
        <div class="border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Administrator</h1>
            <p class="text-gray-600 mt-1 text-sm">Selamat datang, <span class="font-semibold text-gray-800">{{ Auth::user()->name }}</span>. Anda masuk sebagai <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs font-semibold">Administrator</span>.</p>
        </div>

        <div>
            <h2 class="text-lg font-bold text-gray-800 mb-3">Menu Administrator</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Kelola Pengguna & Role -->
                <a href="{{ route('admin.users.index') }}" class="p-4 bg-blue-50 border-2 border-blue-300 rounded-lg hover:bg-blue-100 transition block">
                    <div class="font-bold text-blue-900 mb-1">Kelola Pengguna & Role</div>
                    <p class="text-xs text-gray-600">Tetapkan role akun (Admin, Petugas, Siswa).</p>
                </a>

                <!-- Kelola Data Buku -->
                <a href="{{ route('buku.index') }}" class="p-4 bg-gray-50 border rounded-lg hover:bg-gray-100 transition block">
                    <div class="font-bold text-gray-800 mb-1">Kelola Data Buku</div>
                    <p class="text-xs text-gray-500">Katalog buku, tambah, edit, dan hapus.</p>
                </a>

                <!-- Data Peminjaman & Validasi -->
                <a href="{{ route('peminjaman.index') }}" class="p-4 bg-gray-50 border rounded-lg hover:bg-gray-100 transition block">
                    <div class="font-bold text-gray-800 mb-1">Data Peminjaman</div>
                    <p class="text-xs text-gray-500">Validasi peminjaman dan pengembalian.</p>
                </a>

                <!-- Laporan Perpustakaan -->
                <a href="{{ route('laporan.index') }}" class="p-4 bg-gray-50 border rounded-lg hover:bg-gray-100 transition block">
                    <div class="font-bold text-gray-800 mb-1">Laporan Perpustakaan</div>
                    <p class="text-xs text-gray-500">Rekap buku per kategori & peminjaman.</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
