<x-siswa-layout>
    <div class="p-6">
        <a href="{{ route('siswa.buku.index') }}"
           class="inline-flex items-center gap-2 font-semibold text-red-800 hover:text-red-950">
            <span aria-hidden="true" class="text-xl">&larr;</span> Kembali
        </a>

        <div class="mt-6 max-w-2xl overflow-hidden rounded-lg bg-white p-6 shadow-sm">
            <h1 class="text-2xl font-bold text-gray-800">Detail Buku</h1>

            <dl class="mt-6 divide-y divide-gray-200">
                <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium text-gray-600">ID Buku</dt>
                    <dd class="sm:col-span-2 text-gray-900">{{ $dataBuku['id'] }}</dd>
                </div>
                <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium text-gray-600">Judul</dt>
                    <dd class="sm:col-span-2 text-gray-900">{{ $dataBuku['judul'] }}</dd>
                </div>
                <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium text-gray-600">Penulis</dt>
                    <dd class="sm:col-span-2 text-gray-900">{{ $dataBuku['penulis'] }}</dd>
                </div>
                <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium text-gray-600">Tahun Terbit</dt>
                    <dd class="sm:col-span-2 text-gray-900">{{ $dataBuku['tahun_terbit'] }}</dd>
                </div>
                <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium text-gray-600">Kategori</dt>
                    <dd class="sm:col-span-2 text-gray-900">{{ $dataBuku['kategori'] }}</dd>
                </div>
            </dl>
        </div>
    </div>
</x-siswa-layout>