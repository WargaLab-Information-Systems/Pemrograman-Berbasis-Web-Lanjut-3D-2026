<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
                
                <!-- Form Tambah/Ubah Buku -->
                <h3 id="form-title" class="text-lg font-bold mb-4">Tambah Buku Baru</h3>
                <form id="form-buku" class="mb-8">
                    <input type="hidden" id="buku_id">
                    
                    <div class="mb-4">
                        <label class="block mb-1">Kategori:</label>
                        <select id="kategori_id" class="border p-2 w-full text-black" required>
                            <option value="">-- Pilih Kategori --</option>
                            <!-- Option kategori bisa ditambahkan atau diload dinamis -->
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">Judul Buku:</label>
                        <input type="text" id="judul" class="border p-2 w-full text-black" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">Penulis:</label>
                        <input type="text" id="penulis" class="border p-2 w-full text-black" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">Tahun Terbit:</label>
                        <input type="number" id="tahun_terbit" class="border p-2 w-full text-black" required>
                    </div>

                    <button type="submit" id="btn-submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan Buku</button>
                    <button type="button" id="btn-cancel" onclick="resetForm()" class="bg-gray-500 text-white px-4 py-2 rounded ml-2" style="display: none;">Batal</button>
                </form>

                <!-- Tabel Riwayat Buku -->
                <h3 class="text-lg font-bold mb-2">Riwayat Daftar Buku</h3>
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200 text-black">
                            <th class="border p-2">Kategori</th>
                            <th class="border p-2">Judul</th>
                            <th class="border p-2">Penulis</th>
                            <th class="border p-2">Tahun Terbit</th>
                            <th class="border p-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="buku-table-body">
                        <!-- Data akan di-load otomatis via buku.js -->
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <!-- [PENTING] Memanggil file JavaScript buku.js Anda -->
    @push('scripts')
        <script src="{{ asset('js/buku.js') }}"></script>
    @endpush
</x-app-layout>