@extends('layouts.main')

@section('content')
<div class="container">
    
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="letter-spacing: -0.02em;">Sirkulasi & Pengembalian</h4>
            <p class="text-muted small mb-0">Verifikasi antrean permohonan pinjaman buku dan pembaruan berkas pengembalian.</p>
        </div>
        <span class="badge border bg-white text-secondary py-2 px-3 small">
            Otoritas: Admin & Petugas
        </span>
    </div>

    <div class="panel">
        <div class="table-responsive">
            <table class="table-minimal">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Identitas Siswa</th>
                        <th>Buku</th>
                        <th style="width: 130px;">Tgl Pinjam</th>
                        <th style="width: 130px;">Tgl Kembali</th>
                        <th style="width: 130px;" class="text-center">Status</th>
                        <th style="width: 180px;" class="text-end">Operasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $index => $item)
                    <tr>
                        <td class="text-center text-muted" style="font-size:0.8125rem;">{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $item->user->name }}</div>
                            <div class="text-muted small">{{ $item->user->email }}</div>
                        </td>
                        <td>
                            <div class="fw-medium text-dark">{{ $item->buku->judul }}</div>
                            <div class="text-muted small">{{ $item->buku->penulis }}</div>
                        </td>
                        <td class="text-secondary small" style="font-variant-numeric: tabular-nums;">{{ $item->tanggal_pinjam }}</td>
                        <td class="text-secondary small" style="font-variant-numeric: tabular-nums;">{{ $item->tanggal_kembali ?? '—' }}</td>
                        <td class="text-center">
                            @if($item->status === 'menunggu')
                                <span class="status-pill status-menunggu">Menunggu</span>
                            @elseif($item->status === 'dipinjam')
                                <span class="status-pill status-dipinjam">Dipinjam</span>
                            @elseif($item->status === 'dikembalikan')
                                <span class="status-pill status-dikembalikan">Dikembalikan</span>
                            @else
                                <span class="status-pill status-ditolak">Ditolak</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex align-items-center gap-1">
                                @if($item->status === 'menunggu')
                                    @can('memproses persetujuan peminjaman')
                                    <form action="{{ route('peminjaman.setujui', $item->id) }}" method="POST" class="d-inline mb-0">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-subtle text-success py-1 px-2" style="font-size:0.75rem;">Setujui</button>
                                    </form>
                                    <form action="{{ route('peminjaman.tolak', $item->id) }}" method="POST" class="d-inline mb-0">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-action-danger" style="font-size:0.75rem;">Tolak</button>
                                    </form>
                                    @endcan
                                @elseif($item->status === 'dipinjam')
                                    @can('mencatat pengembalian buku')
                                    <form action="{{ route('peminjaman.kembalikan', $item->id) }}" method="POST" class="d-inline mb-0">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-brand py-1 px-2" style="font-size:0.75rem;">Catat Kembali</button>
                                    </form>
                                    @endcan
                                @else
                                    <span class="text-muted small">Selesai</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted small">Belum ada riwayat transaksi sirkulasi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection