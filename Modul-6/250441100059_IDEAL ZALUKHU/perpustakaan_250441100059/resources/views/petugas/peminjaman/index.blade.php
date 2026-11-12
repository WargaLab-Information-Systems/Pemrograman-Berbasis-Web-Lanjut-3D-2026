
<x-petugas-layout>

    <!-- JUDUL HALAMAN -->
    <div class="admin-welcome">
        <h1 class="admin-page-title">
            Manajemen Peminjaman
        </h1>

        <p>
            Kelola data peminjaman buku perpustakaan.
            Petugas dapat memantau status peminjaman dan mencatat
            pengembalian buku.
        </p>
    </div>

    <!-- TOMBOL KEMBALI -->
    <div style="margin-bottom: 20px;">
        <a href="{{ route('petugas.dashboard') }}"
           class="inline-flex items-center gap-2 font-semibold text-red-800 hover:text-red-950">
            <span aria-hidden="true" class="text-xl">&larr;</span> Kembali
        </a>
    </div>

    <!-- NOTIFIKASI BERHASIL -->
    @if (session('success'))
        <div style="background-color: #dcfce7; color: #166534; padding: 12px; margin-bottom: 16px; border-radius: 6px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- NOTIFIKASI GAGAL -->
    @if (session('error'))
        <div style="background-color: #fee2e2; color: #991b1b; padding: 12px; margin-bottom: 16px; border-radius: 6px;">
            {{ session('error') }}
        </div>
    @endif

    <!-- DAFTAR PEMINJAMAN -->
    <div class="bg-white shadow-sm rounded-lg p-6">

        <h3 class="text-lg font-semibold mb-5 text-gray-800">
            Daftar Seluruh Peminjaman
        </h3>

        <div class="overflow-x-auto">

            <table class="w-full min-w-max border-collapse border border-gray-300">

                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 p-3">No</th>
                        <th class="border border-gray-300 p-3">Nama Anggota</th>
                        <th class="border border-gray-300 p-3">Judul Buku</th>
                        <th class="border border-gray-300 p-3">Tanggal Pinjam</th>
                        <th class="border border-gray-300 p-3">Tanggal Kembali</th>
                        <th class="border border-gray-300 p-3">Status</th>
                        <th class="border border-gray-300 p-3">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($peminjamans as $peminjaman)

                        <tr class="hover:bg-gray-50">

                            <td class="border border-gray-300 p-3 text-center">
                                {{ $loop->iteration }}
                            </td>

                            <td class="border border-gray-300 p-3">
                                {{ $peminjaman->anggota->nama ?? '-' }}
                            </td>

                            <td class="border border-gray-300 p-3">
                                {{ $peminjaman->buku->judul ?? '-' }}
                            </td>

                            <td class="border border-gray-300 p-3 text-center">
                                {{ $peminjaman->tanggal_pinjam }}
                            </td>

                            <td class="border border-gray-300 p-3 text-center">
                                {{ $peminjaman->tanggal_kembali ?? '-' }}
                            </td>

                            <!-- STATUS PEMINJAMAN -->
                            <td class="border border-gray-300 p-3 text-center">

                                @if ($peminjaman->status === 'menunggu')
                                    <span style="color: #b45309; font-weight: 600;">Menunggu Persetujuan</span>
                                @elseif ($peminjaman->status === 'ditolak')
                                    <span style="color: #b91c1c; font-weight: 600;">Ditolak</span>
                                @elseif ($peminjaman->status === 'dikembalikan' || $peminjaman->tanggal_kembali)

                                    <span style="color: #166534; font-weight: 600;">
                                        Sudah Dikembalikan
                                    </span>

                                @elseif (in_array($peminjaman->status, ['disetujui', 'dipinjam'], true))
                                    <span class="inline-flex items-center gap-1 rounded-full border border-blue-200 bg-blue-50 px-2.5 py-1 font-semibold text-blue-700">
                                        <span aria-hidden="true">●</span> Sedang Dipinjam
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full border border-slate-300 px-2.5 py-1 font-semibold text-slate-600">{{ ucfirst($peminjaman->status) }}</span>

                                @endif

                            </td>

                            <!-- AKSI -->
                            <td class="border border-gray-300 p-3 text-center">

                                @if ($peminjaman->status === 'menunggu')
                                    <div class="flex flex-wrap justify-center gap-2">
                                        <form action="{{ route('petugas.peminjaman.proses', [$peminjaman, 'disetujui']) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="rounded-md border border-green-700 bg-green-50 px-3 py-1.5 font-semibold text-green-700 hover:bg-green-100">✓ Setujui</button>
                                        </form>
                                        <form action="{{ route('petugas.peminjaman.proses', [$peminjaman, 'ditolak']) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="rounded-md border border-red-700 bg-red-50 px-3 py-1.5 font-semibold text-red-700 hover:bg-red-100">× Tolak</button>
                                        </form>
                                    </div>
                                @elseif (in_array($peminjaman->status, ['disetujui', 'dipinjam'], true) && !$peminjaman->tanggal_kembali)

                                    <form
                                        action="{{ route('petugas.peminjaman.kembalikan', $peminjaman->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Apakah buku ini sudah dikembalikan?')"
                                    >

                                        @csrf

                                        <button
                                            type="submit"
                                            class="rounded-md border border-blue-700 bg-blue-50 px-3 py-1.5 font-semibold text-blue-700 hover:bg-blue-100"
                                        >
                                            ↩ Kembalikan
                                        </button>

                                    </form>

                                @else

                                    <span class="inline-flex rounded-full border border-green-200 bg-green-50 px-2.5 py-1 font-semibold text-green-700">
                                        ✓ Selesai
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7"
                                class="border border-gray-300 p-4 text-center text-gray-500">
                                Belum ada data peminjaman.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</x-petugas-layout>
