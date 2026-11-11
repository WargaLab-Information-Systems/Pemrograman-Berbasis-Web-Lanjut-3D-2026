@extends('layouts.main')

@section('title', 'Riwayat Peminjaman Saya')

@section('content')
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-primary">
            <i class="bi bi-clock-history me-2"></i>Riwayat Peminjaman Saya
        </h5>
        <a href="{{ route('buku.index') }}" class="btn btn-sm btn-primary rounded-pill">
            <i class="bi bi-book me-1"></i> Pinjam Buku Lain
        </a>
    </div>

    <div class="card-body p-4">
        <p class="text-muted mb-4">
            Menampilkan riwayat peminjaman buku khusus untuk akun: <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }})
        </p>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary text-uppercase fs-7">
                    <tr>
                        <th class="ps-3" style="width: 35%;">Judul Buku</th>
                        <th style="width: 20%;">Tanggal Pinjam</th>
                        <th style="width: 25%;">Tanggal Kembali</th>
                        <th class="text-center" style="width: 20%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $item)
                        <tr>
                            <td class="ps-3 fw-semibold text-dark">
                                {{ $item->buku->judul ?? 'Buku #'.$item->buku_id }}
                            </td>
                            <td class="text-secondary">{{ $item->tanggal_pinjam }}</td>
                            <td class="text-secondary">{{ $item->tanggal_kembali ?? '-' }}</td>
                            <td class="text-center">
                                @if($item->tanggal_kembali)
                                    <span class="badge bg-success px-3 py-2 rounded-pill">
                                        <i class="bi bi-check-circle-fill me-1"></i> Selesai Dikembalikan
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                        <i class="bi bi-hourglass-split me-1"></i> Sedang Dipinjam
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                Anda belum memiliki riwayat peminjaman buku.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
