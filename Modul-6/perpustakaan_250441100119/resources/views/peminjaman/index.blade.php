@extends('layouts.app')

@section('title', 'Peminjaman Buku')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
     style="padding-top:36px;padding-bottom:32px;">

    <h1 class="text-2xl font-bold text-gray-800 mb-2">
        @can('kelola peminjaman')
            Kelola Peminjaman Buku
        @else
            Peminjaman Buku Saya
        @endcan
    </h1>

    <p class="text-gray-600 mb-6">
        @can('kelola peminjaman')
            Kelola pengajuan, persetujuan, dan pengembalian buku.
        @else
            Ajukan peminjaman dan lihat riwayat buku milikmu.
        @endcan
    </p>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Form pengajuan peminjaman untuk Siswa --}}
    @role('siswa')
        @can('ajukan peminjaman buku')

            <div class="bg-white shadow rounded-lg p-6"
                 style="margin-bottom:24px;">

                <h2 class="text-lg font-semibold mb-4">
                    Ajukan Peminjaman Buku
                </h2>

                <form
                    action="{{ route('peminjaman.store') }}"
                    method="POST"
                    class="space-y-4"
                >
                    @csrf

                    <div>
                        <label
                            for="buku_id"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Pilih Buku
                        </label>

                        <select
                            id="buku_id"
                            name="buku_id"
                            required
                            class="w-full rounded-md border-gray-300"
                        >
                            <option value="">-- Pilih Buku --</option>

                            @foreach ($bukus as $buku)
                                <option
                                    value="{{ $buku->id }}"
                                    @selected(old('buku_id') == $buku->id)
                                >
                                    {{ $buku->judul }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if (!$anggota)
                        <div>
                            <label
                                for="no_telepon"
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Nomor Telepon
                            </label>

                            <input
                                type="text"
                                id="no_telepon"
                                name="no_telepon"
                                value="{{ old('no_telepon') }}"
                                placeholder="Contoh: 081234567890"
                                required
                                class="w-full rounded-md border-gray-300"
                            >
                        </div>
                    @endif

                    <div style="padding-top:8px;padding-bottom:8px;">
                        <button
                            type="submit"
                            style="background-color:#2563eb;color:white;padding:10px 18px;border-radius:6px;font-weight:600;cursor:pointer;"
                        >
                            Ajukan Peminjaman
                        </button>
                    </div>

                </form>

            </div>

        @endcan
    @endrole

    {{-- Daftar peminjaman --}}
    <div class="bg-white shadow rounded-lg overflow-hidden"
         style="margin-top:24px;">

        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                @can('kelola peminjaman')
                    Daftar Semua Peminjaman
                @else
                    Riwayat Peminjaman Saya
                @endcan
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] divide-y divide-gray-200">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">No.</th>

                        @can('kelola peminjaman')
                            <th class="px-4 py-3 text-left">
                                Anggota
                            </th>
                        @endcan

                        <th class="px-4 py-3 text-left">
                            Judul Buku
                        </th>

                        <th class="px-4 py-3 text-left">
                            Tanggal Pinjam
                        </th>

                        <th class="px-4 py-3 text-left">
                            Tanggal Kembali
                        </th>

                        <th class="px-4 py-3 text-left">
                            Status
                        </th>

                        @canany([
                            'proses persetujuan peminjaman',
                            'catat pengembalian buku'
                        ])
                            <th class="px-4 py-3 text-center">
                                Aksi
                            </th>
                        @endcanany
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">

                    @forelse ($peminjamans as $peminjaman)
                        <tr>

                            <td class="px-4 py-4">
                                {{ $peminjamans->firstItem() + $loop->index }}
                            </td>

                            @can('kelola peminjaman')
                                <td class="px-4 py-4">
                                    {{ $peminjaman->anggota?->nama ?? '-' }}
                                </td>
                            @endcan

                            <td class="px-4 py-4">
                                {{ $peminjaman->buku?->judul ?? '-' }}
                            </td>

                            <td class="px-4 py-4">
                                {{ $peminjaman->tanggal_pinjam }}
                            </td>

                            <td class="px-4 py-4">
                                {{ $peminjaman->tanggal_kembali ?? '-' }}
                            </td>

                            <td class="px-4 py-4">

                                @if ($peminjaman->status === 'menunggu')
                                    <span class="text-yellow-700 font-semibold">
                                        Menunggu
                                    </span>

                                @elseif ($peminjaman->status === 'disetujui')
                                    <span class="text-green-700 font-semibold">
                                        Disetujui
                                    </span>

                                @elseif ($peminjaman->status === 'ditolak')
                                    <span class="text-red-700 font-semibold">
                                        Ditolak
                                    </span>

                                @elseif ($peminjaman->status === 'dikembalikan')
                                    <span class="text-blue-700 font-semibold">
                                        Dikembalikan
                                    </span>

                                @else
                                    <span class="text-gray-700 font-semibold">
                                        {{ ucfirst($peminjaman->status) }}
                                    </span>
                                @endif

                            </td>

                            @canany([
                                'proses persetujuan peminjaman',
                                'catat pengembalian buku'
                            ])
                                <td class="px-4 py-4 text-center">

                                    @if ($peminjaman->status === 'menunggu')

                                        @can('proses persetujuan peminjaman')

                                            <div class="flex justify-center gap-2">

                                                <form
                                                    action="{{ route('peminjaman.update-status', $peminjaman) }}"
                                                    method="POST"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <input
                                                        type="hidden"
                                                        name="status"
                                                        value="disetujui"
                                                    >

                                                    <button
                                                        type="submit"
                                                        style="background-color:#16a34a;color:white;padding:8px 12px;border-radius:6px;cursor:pointer;"
                                                    >
                                                        Setujui
                                                    </button>
                                                </form>

                                                <form
                                                    action="{{ route('peminjaman.update-status', $peminjaman) }}"
                                                    method="POST"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <input
                                                        type="hidden"
                                                        name="status"
                                                        value="ditolak"
                                                    >

                                                    <button
                                                        type="submit"
                                                        style="background-color:#dc2626;color:white;padding:8px 12px;border-radius:6px;cursor:pointer;"
                                                    >
                                                        Tolak
                                                    </button>
                                                </form>

                                            </div>

                                        @else
                                            -
                                        @endcan

                                    @elseif (in_array($peminjaman->status, ['disetujui', 'dipinjam']))

                                        @can('catat pengembalian buku')

                                            <form
                                                action="{{ route('peminjaman.kembalikan', $peminjaman) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin buku ini sudah dikembalikan?')"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    style="background-color:#2563eb;color:white;padding:8px 12px;border-radius:6px;cursor:pointer;"
                                                >
                                                    Kembalikan
                                                </button>
                                            </form>

                                        @else
                                            -
                                        @endcan

                                    @else
                                        -
                                    @endif

                                </td>
                            @endcanany

                        </tr>

                    @empty
                        <tr>
                            <td
                                colspan="7"
                                class="px-4 py-6 text-center text-gray-500"
                            >
                                Belum ada data peminjaman.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $peminjamans->links() }}
    </div>

</div>

@endsection