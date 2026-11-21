<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Admin</h2>
    </x-slot>

    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500 mb-8">
        <h3 class="text-xl font-bold text-gray-900">Area Khusus Administrator</h3>
        <p class="text-gray-600 mt-2">Halaman ini dilindungi secara ketat. Anda memiliki kendali penuh atas sistem.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
            <h4 class="font-bold text-gray-800 text-lg">Manajemen Pengguna</h4>
            <p class="text-sm text-gray-500 mt-2 mb-4">Penetapan hak akses & kelola akun staf.</p>
            <a href="{{ url('/admin/pengguna') }}" class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-800">
                Buka Kelola Pengguna &rarr;
            </a>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
            <h4 class="font-bold text-gray-800 text-lg">Laporan Sirkulasi</h4>
            <p class="text-sm text-gray-500 mt-2 mb-4">Rekap menyeluruh transaksi perpustakaan.</p>
            <a href="{{ url('/laporan') }}" class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-800">
                Buka Laporan &rarr;
            </a>
        </div>
    </div>
</x-app-layout>