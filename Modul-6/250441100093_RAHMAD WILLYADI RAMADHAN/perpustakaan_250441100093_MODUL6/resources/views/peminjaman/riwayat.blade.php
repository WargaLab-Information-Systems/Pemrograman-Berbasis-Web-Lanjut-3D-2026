<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Riwayat Saya</h2>
    </x-slot>

    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500 mb-8">
        <h3 class="text-xl font-bold text-gray-900">Riwayat Peminjaman Buku</h3>
        <p class="text-gray-600 mt-2">Daftar buku yang pernah Anda ajukan dan pinjam.</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-700">
                    <th class="p-3 border-b">Buku</th>
                    <th class="p-3 border-b">Tanggal Pengajuan</th>
                    <th class="p-3 border-b">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $item)
                <tr>
                    <td class="p-3 border-b">{{ $item->buku->judul_buku ?? $item->buku->judul ?? 'Buku Dihapus' }}</td>
                    <td class="p-3 border-b">{{ $item->tanggal_pinjam }}</td>
                    <td class="p-3 border-b font-bold">{{ $item->status }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="p-3 border-b text-center text-gray-500">Belum ada riwayat peminjaman buku.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>