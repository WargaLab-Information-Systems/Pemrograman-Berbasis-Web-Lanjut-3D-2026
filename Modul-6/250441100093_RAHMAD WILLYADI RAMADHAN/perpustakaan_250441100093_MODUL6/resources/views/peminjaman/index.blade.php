<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Peminjaman</h2>
    </x-slot>

    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500 mb-8">
        <h3 class="text-xl font-bold text-gray-900">Data Sirkulasi Peminjaman</h3>
        <p class="text-gray-600 mt-2">Halaman manajemen persetujuan peminjaman dan pencatatan pengembalian buku.</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-700">
                    <th class="p-3 border-b border-gray-200">Nama Peminjam</th>
                    <th class="p-3 border-b border-gray-200">Buku</th>
                    <th class="p-3 border-b border-gray-200">Tanggal Pinjam</th>
                    <th class="p-3 border-b border-gray-200">Status</th>
                    <th class="p-3 border-b border-gray-200 w-48">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataPeminjaman as $item)
                <tr>
                    <td class="p-3 border-b border-gray-200 align-middle">{{ $item->anggota->name ?? 'Pengguna Dihapus' }}</td>
                    <td class="p-3 border-b border-gray-200 align-middle">{{ $item->buku->judul_buku ?? $item->buku->judul ?? 'Buku Dihapus' }}</td>
                    <td class="p-3 border-b border-gray-200 align-middle">{{ $item->tanggal_pinjam }}</td>
                    <td class="p-3 border-b border-gray-200 align-middle font-bold">{{ $item->status }}</td>
                    
                    <td class="p-3 border-b border-gray-200 align-middle w-48 whitespace-nowrap">
                        <div class="flex items-center gap-2 min-h-[32px]">
                            @if($item->status == 'Menunggu Persetujuan')
                                <form action="{{ url('/peminjaman/'.$item->id) }}" method="POST" class="m-0 inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="Disetujui">
                                    <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">Setujui</button>
                                </form>
                                <form action="{{ url('/peminjaman/'.$item->id) }}" method="POST" class="m-0 inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="Ditolak">
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-md text-sm hover:bg-red-700">Tolak</button>
                                </form>
                            @elseif($item->status == 'Disetujui')
                                <form action="{{ url('/peminjaman/'.$item->id) }}" method="POST" class="m-0 inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="Dikembalikan">
                                    <button type="submit" class="px-3 py-1 bg-gray-800 text-white rounded-md text-sm hover:bg-gray-900">Selesaikan</button>
                                </form>
                            @else
                                <span class="text-gray-400 font-bold px-2">-</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500 border-b border-gray-200">Belum ada pengajuan peminjaman saat ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>