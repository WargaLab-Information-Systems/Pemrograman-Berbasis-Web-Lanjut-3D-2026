@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/buku.css') }}">

<div class="buku-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; color: #342a26; margin: 0;">Kelola Peminjaman & Pengembalian</h2>
            <p style="color: #666; font-size: 0.875rem; margin: 4px 0 0 0;">Proses persetujuan peminjaman buku dan pencatatan pengembalian buku.</p>
        </div>
        <div>
            @can('Mengajukan peminjaman buku')
                <a href="{{ route('peminjaman.create') }}" class="btn-primary-custom" style="text-decoration: none;">
                    + Input Peminjaman Baru
                </a>
            @endcan
        </div>
    </div>

    <!-- Filter Status Tab -->
    <div style="display: flex; gap: 8px; margin-bottom: 15px; overflow-x: auto; padding-bottom: 4px;">
        <a href="{{ route('peminjaman.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-decoration-none {{ !request('status') ? 'bg-[#342a26] text-white' : 'bg-white text-gray-700 border border-gray-300' }}">
            Semua Status
        </a>
        <a href="{{ route('peminjaman.index', ['status' => 'menunggu']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-decoration-none {{ request('status') === 'menunggu' ? 'bg-[#d4a373] text-white' : 'bg-white text-gray-700 border border-gray-300' }}">
            Menunggu Persetujuan
        </a>
        <a href="{{ route('peminjaman.index', ['status' => 'disetujui']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-decoration-none {{ request('status') === 'disetujui' ? 'bg-[#bc6c25] text-white' : 'bg-white text-gray-700 border border-gray-300' }}">
            Sedang Dipinjam
        </a>
        <a href="{{ route('peminjaman.index', ['status' => 'dikembalikan']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-decoration-none {{ request('status') === 'dikembalikan' ? 'bg-[#2a9d8f] text-white' : 'bg-white text-gray-700 border border-gray-300' }}">
            Sudah Dikembalikan
        </a>
    </div>

    <!-- Tabel Peminjaman dengan Styling Konsisten -->
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Nama Peminjam</th>
                    <th>Judul Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                    <th>Aksi Operasional</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamans as $p)
                    <tr>
                        <td>
                            <strong style="color: #2b2b2b;">{{ $p->user->name ?? $p->anggota->nama ?? 'Anggota #' . $p->anggota_id }}</strong>
                            <div style="font-size: 0.75rem; color: #888;">{{ $p->user->email ?? $p->anggota->email ?? '' }}</div>
                        </td>
                        <td>
                            <strong style="color: #2b2b2b;">{{ $p->buku->judul ?? '-' }}</strong>
                            <div style="font-size: 0.75rem; color: #888;">{{ $p->buku->penulis ?? '-' }}</div>
                        </td>
                        <td>{{ $p->tanggal_pinjam }}</td>
                        <td>{{ $p->tanggal_kembali ?? '-' }}</td>
                        <td>
                            @if($p->status === 'menunggu')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Menunggu</span>
                            @elseif($p->status === 'disetujui' && !$p->tanggal_kembali)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">Dipinjam</span>
                            @elseif($p->status === 'dikembalikan' || $p->tanggal_kembali)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Dikembalikan</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Ditolak</span>
                            @endif
                        </td>
                        <td>
                            @if($p->status === 'menunggu')
                                @can('Memproses persetujuan peminjaman')
                                    <form method="POST" action="{{ route('peminjaman.setujui', $p->id) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-ubah" style="background-color: #2a9d8f;">Setujui</button>
                                    </form>
                                    <form method="POST" action="{{ route('peminjaman.tolak', $p->id) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-hapus" style="background-color: #e76f51;">Tolak</button>
                                    </form>
                                @endcan
                            @endif

                            @if($p->status === 'disetujui' && !$p->tanggal_kembali)
                                @can('Mencatat pengembalian buku')
                                    <form method="POST" action="{{ route('peminjaman.kembalikan', $p->id) }}" style="display: inline;" onsubmit="return confirm('Catat pengembalian buku ini?');">
                                        @csrf
                                        <button type="submit" class="btn-ubah" style="background-color: #bc6c25;">Catat Kembali</button>
                                    </form>
                                @endcan
                            @endif

                            <a href="{{ route('peminjaman.show', $p->id) }}" class="btn-detail" style="margin-left: 4px; font-size: 0.75rem; padding: 4px 10px;">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="baris-info">Tidak ada data peminjaman tercatat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 15px;">
        {{ $peminjamans->links() }}
    </div>
</div>
@endsection
