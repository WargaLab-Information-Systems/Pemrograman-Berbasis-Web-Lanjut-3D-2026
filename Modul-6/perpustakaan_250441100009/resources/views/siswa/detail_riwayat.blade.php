<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Riwayat Peminjaman Buku
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-4">
                <a href="{{ route('siswa.riwayat') }}" class="text-xs font-semibold text-rose-700 hover:underline">&larr; Kembali ke Riwayat Saya</a>

                <h3 class="text-xl font-bold text-gray-900 border-b pb-3">Informasi Peminjaman</h3>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500 block text-xs">Peminjam</span>
                        <span class="font-semibold text-gray-800">{{ $peminjaman->user?->name }} ({{ $peminjaman->user?->email }})</span>
                    </div>

                    <div>
                        <span class="text-gray-500 block text-xs">Status Peminjaman</span>
                        <span class="font-semibold text-gray-800 uppercase text-xs">
                            {{ $peminjaman->status }}
                        </span>
                    </div>

                    <div>
                        <span class="text-gray-500 block text-xs">Judul Buku</span>
                        <span class="font-semibold text-gray-800">{{ $peminjaman->buku?->judul }}</span>
                    </div>

                    <div>
                        <span class="text-gray-500 block text-xs">Penulis</span>
                        <span class="font-semibold text-gray-800">{{ $peminjaman->buku?->penulis }}</span>
                    </div>

                    <div>
                        <span class="text-gray-500 block text-xs">Tahun Terbit</span>
                        <span class="font-semibold text-gray-800">{{ $peminjaman->buku?->tahun_terbit }}</span>
                    </div>

                    <div>
                        <span class="text-gray-500 block text-xs">Tanggal Pinjam</span>
                        <span class="font-semibold text-gray-800">{{ $peminjaman->tanggal_pinjam }}</span>
                    </div>

                    <div>
                        <span class="text-gray-500 block text-xs">Tanggal Pengembalian</span>
                        <span class="font-semibold text-gray-800">{{ $peminjaman->tanggal_kembali ?? 'Belum Dikembalikan' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
