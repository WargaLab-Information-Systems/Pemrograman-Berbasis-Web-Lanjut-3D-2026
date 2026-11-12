<x-app-layout>
    <div class="bg-white p-6 rounded-lg shadow-md max-w-2xl mx-auto space-y-6">
        <div class="flex flex-wrap items-center justify-between border-b pb-4 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Transaksi Peminjaman</h1>
                <p class="text-gray-600 text-sm mt-1">Informasi lengkap peminjaman buku perpustakaan.</p>
            </div>
            @role('siswa')
                <a href="{{ route('peminjaman.riwayat', Auth::id()) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm transition">
                    &larr; Riwayat Peminjaman
                </a>
            @else
                <a href="{{ route('peminjaman.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm transition">
                    &larr; Data Peminjaman
                </a>
            @endrole
        </div>

        <div class="bg-gray-50 p-6 rounded border space-y-3 text-sm">
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-600">ID Transaksi:</span>
                <span class="font-bold text-gray-800">#{{ $peminjaman->id }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-600">Nama Peminjam:</span>
                <span class="font-semibold text-gray-900">{{ $peminjaman->anggota->nama ?? '-' }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-600">Email Akun Peminjam:</span>
                <span class="text-gray-700">{{ $peminjaman->anggota->email ?? '-' }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-600">Judul Buku:</span>
                <span class="font-semibold text-blue-700">{{ $peminjaman->buku->judul ?? '-' }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-600">Penulis Buku:</span>
                <span class="text-gray-700">{{ $peminjaman->buku->penulis ?? '-' }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-600">Tanggal Peminjaman:</span>
                <span class="text-gray-800">{{ $peminjaman->tanggal_pinjam }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-600">Tanggal Pengembalian:</span>
                <span class="text-gray-800">{{ $peminjaman->tanggal_kembali ?? 'Belum Dikembalikan' }}</span>
            </div>
            <div class="flex justify-between pt-1">
                <span class="text-gray-600">Status Validasi:</span>
                <div>
                    @php
                        $st = $peminjaman->status ?? ($peminjaman->tanggal_kembali ? 'kembali' : 'dipinjam');
                    @endphp
                    @if($st === 'menunggu')
                        <span class="bg-orange-100 text-orange-800 px-2.5 py-1 rounded-full text-xs font-semibold">⏳ Menunggu Persetujuan</span>
                    @elseif($st === 'dipinjam')
                        <span class="bg-yellow-100 text-yellow-800 px-2.5 py-1 rounded-full text-xs font-semibold">📖 Sedang Dipinjam</span>
                    @elseif($st === 'ditolak')
                        <span class="bg-red-100 text-red-800 px-2.5 py-1 rounded-full text-xs font-semibold">✕ Ditolak</span>
                    @else
                        <span class="bg-green-100 text-green-800 px-2.5 py-1 rounded-full text-xs font-semibold">✓ Sudah Dikembalikan</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
