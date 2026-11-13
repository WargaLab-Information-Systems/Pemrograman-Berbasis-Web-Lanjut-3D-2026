@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/buku.css') }}">

<div class="buku-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; color: #342a26; margin: 0;">Riwayat Peminjaman Buku Saya</h2>
            <p style="color: #666; font-size: 0.875rem; margin: 4px 0 0 0;">Daftar buku yang pernah dan sedang Anda pinjam di perpustakaan.</p>
        </div>
        <div>
            @can('Mengajukan peminjaman buku')
                <a href="{{ route('peminjaman.create') }}" class="btn-primary-custom" style="text-decoration: none;">
                    + Ajukan Peminjaman Baru
                </a>
            @endcan
        </div>
    </div>

    <!-- Tabel Riwayat Pribadi Sesuai Desain Konsisten -->
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Buku Dipinjam</th>
                    <th>Penulis</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status Peminjaman</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamans as $p)
                    <tr>
                        <td>
                            <strong style="color: #2b2b2b;">{{ $p->buku->judul ?? '-' }}</strong>
                        </td>
                        <td>{{ $p->buku->penulis ?? '-' }}</td>
                        <td>{{ $p->tanggal_pinjam }}</td>
                        <td>{{ $p->tanggal_kembali ?? 'Belum Kembali' }}</td>
                        <td>
                            @if($p->status === 'menunggu')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Menunggu Konfirmasi</span>
                            @elseif($p->status === 'disetujui' && !$p->tanggal_kembali)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">Sedang Dipinjam</span>
                            @elseif($p->status === 'dikembalikan' || $p->tanggal_kembali)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Sudah Dikembalikan</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Ditolak</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('peminjaman.show', $p->id) }}" class="btn-detail">Lihat Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="baris-info">
                            Anda belum memiliki riwayat peminjaman buku.
                        </td>
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
