@extends('layouts.main')

@section('content')
<div class="container">
    
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="letter-spacing: -0.02em;">Riwayat & Status Pinjaman</h4>
            <p class="text-muted small mb-0">Daftar arsip sirkulasi buku yang diajukan oleh akun Anda.</p>
        </div>
        <span class="badge border bg-white text-secondary py-2 px-3 small">
            ID Anggota: #{{ $user->id }}
        </span>
    </div>

    <div class="panel">
        <div class="table-responsive">
            <table class="table-minimal">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Koleksi Buku</th>
                        <th style="width: 160px;">Kategori</th>
                        <th style="width: 140px;">Tgl Pinjam</th>
                        <th style="width: 140px;">Tgl Kembali</th>
                        <th style="width: 150px;" class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayats as $index => $item)
                    <tr>
                        <td class="text-center text-muted" style="font-size:0.8125rem;">{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $item->buku->judul }}</div>
                            <div class="text-muted small">{{ $item->buku->penulis }}</div>
                        </td>
                        <td>
                            <span class="badge border bg-light text-secondary fw-normal px-2 py-1" style="font-size:0.75rem;">
                                {{ $item->buku->kategori ? $item->buku->kategori->nama : 'Umum' }}
                            </span>
                        </td>
                        <td class="text-secondary small" style="font-variant-numeric: tabular-nums;">{{ $item->tanggal_pinjam }}</td>
                        <td class="text-secondary small" style="font-variant-numeric: tabular-nums;">{{ $item->tanggal_kembali ?? '—' }}</td>
                        <td class="text-center">
                            @if($item->status === 'menunggu')
                                <span class="status-pill status-menunggu">Menunggu Verifikasi</span>
                            @elseif($item->status === 'dipinjam')
                                <span class="status-pill status-dipinjam">Sedang Dipinjam</span>
                            @elseif($item->status === 'dikembalikan')
                                <span class="status-pill status-dikembalikan">Telah Dikembalikan</span>
                            @else
                                <span class="status-pill status-ditolak">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted small">
                            Anda belum pernah mengajukan peminjaman buku. 
                            <br><a href="{{ route('buku.index') }}" class="text-dark fw-medium mt-1 d-inline-block">Lihat Koleksi Buku →</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection