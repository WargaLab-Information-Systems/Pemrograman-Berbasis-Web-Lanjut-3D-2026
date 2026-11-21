<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            @role('siswa')
                Riwayat Peminjaman Saya
            @else
                Data Peminjaman Perpustakaan
            @endrole
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">

                {{-- Pesan berhasil --}}
                @if (session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Pesan gagal --}}
                @if (session('error'))
                    <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Pesan validasi --}}
                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Rekap Admin dan Petugas --}}
                @hasanyrole('admin|petugas')
                    <h3 class="font-semibold text-lg mb-4">
                        Rekap Peminjaman
                    </h3>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Total Peminjaman</p>
                            <h3 class="text-2xl font-bold">{{ $rekap->total }}</h3>
                        </div>

                        <div class="bg-yellow-100 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Menunggu</p>
                            <h3 class="text-2xl font-bold">{{ $rekap->menunggu }}</h3>
                        </div>

                        <div class="bg-blue-100 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Sedang Dipinjam</p>
                            <h3 class="text-2xl font-bold">{{ $rekap->dipinjam }}</h3>
                        </div>

                        <div class="bg-orange-100 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Pengembalian Diajukan</p>
                            <h3 class="text-2xl font-bold">{{ $rekap->pengembalian_diajukan }}</h3>
                        </div>

                        <div class="bg-green-100 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Dikembalikan</p>
                            <h3 class="text-2xl font-bold">{{ $rekap->dikembalikan }}</h3>
                        </div>

                        <div class="bg-red-100 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Ditolak</p>
                            <h3 class="text-2xl font-bold">{{ $rekap->ditolak }}</h3>
                        </div>
                    </div>
                @endhasanyrole

                {{-- Form pengajuan peminjaman untuk siswa --}}
                @role('siswa')
                    <div class="border rounded-lg p-4 mb-6">
                        <h3 class="font-semibold mb-3">
                            Ajukan Peminjaman Buku
                        </h3>

                        <form action="{{ route('peminjaman.store') }}" method="POST">
                            @csrf

                            <select name="buku_id"
                                class="w-full border-gray-300 rounded-md mb-3"
                                required>
                                <option value="">Pilih Buku</option>

                                @foreach ($bukus as $buku)
                                    <option value="{{ $buku->id }}">
                                        {{ $buku->judul }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                Ajukan Peminjaman
                            </button>
                        </form>
                    </div>
                @endrole

                {{-- Pencarian dan filter --}}
                <form action="{{ route('peminjaman.index') }}"
                    method="GET"
                    class="flex flex-col md:flex-row gap-3 mb-6">

                    <input
                        type="text"
                        name="cari"
                        value="{{ request('cari') }}"
                        placeholder="Cari nama anggota atau judul buku..."
                        class="border-gray-300 rounded-md flex-1">

                    <select name="status" class="border-gray-300 rounded-md">
                        <option value="">Semua Status</option>

                        <option value="menunggu" @selected(request('status') == 'menunggu')>
                            Menunggu
                        </option>

                        <option value="dipinjam" @selected(request('status') == 'dipinjam')>
                            Dipinjam
                        </option>

                        <option value="pengembalian_diajukan" @selected(request('status') == 'pengembalian_diajukan')>
                            Pengembalian Diajukan
                        </option>

                        <option value="dikembalikan" @selected(request('status') == 'dikembalikan')>
                            Dikembalikan
                        </option>

                        <option value="ditolak" @selected(request('status') == 'ditolak')>
                            Ditolak
                        </option>
                    </select>

                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                        Cari
                    </button>

                    <a href="{{ route('peminjaman.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-md text-center">
                        Reset
                    </a>
                </form>

                {{-- Tabel peminjaman --}}
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border text-sm">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-3">No</th>
                                <th class="border p-3">Nama Anggota</th>
                                <th class="border p-3">Judul Buku</th>
                                <th class="border p-3">Tanggal Pinjam</th>
                                <th class="border p-3">Tanggal Kembali</th>
                                <th class="border p-3">Status</th>

                                @if (auth()->user()->hasAnyRole(['admin', 'petugas', 'siswa']))
                                    <th class="border p-3">Aksi</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($peminjamans as $peminjaman)
                                <tr>
                                    <td class="border p-3">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $peminjaman->anggota->nama ?? '-' }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $peminjaman->buku->judul ?? '-' }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $peminjaman->tanggal_pinjam }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $peminjaman->tanggal_kembali ?? 'Belum dikembalikan' }}
                                    </td>

                                    <td class="border p-3">
                                        @if ($peminjaman->status === 'menunggu')
                                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded">
                                                Menunggu
                                            </span>
                                        @elseif ($peminjaman->status === 'dipinjam')
                                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded">
                                                Dipinjam
                                            </span>
                                        @elseif ($peminjaman->status === 'pengembalian_diajukan')
                                            <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded">
                                                Pengembalian Diajukan
                                            </span>
                                        @elseif ($peminjaman->status === 'dikembalikan')
                                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded">
                                                Dikembalikan
                                            </span>
                                        @elseif ($peminjaman->status === 'ditolak')
                                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded">
                                                Ditolak
                                            </span>
                                        @else
                                            {{ $peminjaman->status }}
                                        @endif
                                    </td>

                                    {{-- Kolom aksi --}}
                                    @if (auth()->user()->hasAnyRole(['admin', 'petugas', 'siswa']))
                                        <td class="border p-3">

                                            {{-- Aksi siswa --}}
                                            @role('siswa')
                                                @if ($peminjaman->status === 'dipinjam')
                                                    <form
                                                        action="{{ route('peminjaman.requestReturn', $peminjaman->id) }}"
                                                        method="POST">
                                                        @csrf

                                                        <button type="submit"
                                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded">
                                                            Ajukan Pengembalian
                                                        </button>
                                                    </form>
                                                @elseif ($peminjaman->status === 'menunggu')
                                                    <span class="text-yellow-700">
                                                        Menunggu persetujuan
                                                    </span>
                                                @elseif ($peminjaman->status === 'pengembalian_diajukan')
                                                    <span class="text-orange-700">
                                                        Menunggu konfirmasi petugas
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            @endrole

                                            {{-- Aksi Admin dan Petugas --}}
                                            @hasanyrole('admin|petugas')
                                                @can('process_borrowing_approval')
                                                    @if ($peminjaman->status === 'menunggu')
                                                        <form
                                                            action="{{ route('peminjaman.approve', $peminjaman->id) }}"
                                                            method="POST"
                                                            class="mb-2">
                                                            @csrf
                                                            @method('PATCH')

                                                            <button type="submit"
                                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded">
                                                                Setujui
                                                            </button>
                                                        </form>

                                                        <form
                                                            action="{{ route('peminjaman.reject', $peminjaman->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PATCH')

                                                            <button type="submit"
                                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded">
                                                                Tolak
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endcan

                                                @can('record_return')
                                                    @if ($peminjaman->status === 'pengembalian_diajukan')
                                                        <form
                                                            action="{{ route('peminjaman.confirmReturn', $peminjaman->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PATCH')

                                                            <button type="submit"
                                                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded">
                                                                Konfirmasi Pengembalian
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endcan

                                                @if (
                                                    $peminjaman->status !== 'menunggu' &&
                                                    $peminjaman->status !== 'pengembalian_diajukan'
                                                )
                                                    -
                                                @endif
                                            @endhasanyrole

                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="border p-4 text-center text-gray-500">
                                        Belum ada data peminjaman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
