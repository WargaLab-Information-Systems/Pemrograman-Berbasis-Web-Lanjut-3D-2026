@extends('layouts.main')

@section('content')
<div class="container" style="max-width: 760px;">
    
    <div class="mb-3">
        <a href="{{ route('buku.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1">
            <span>←</span> Kembali ke Katalog
        </a>
    </div>

    <div class="panel">
        <div class="panel-header d-flex justify-content-between align-items-center">
            <div>
                <span class="badge border bg-light text-secondary fw-normal px-2 py-1 mb-1" style="font-size:0.75rem;">
                    {{ $buku->kategori ? $buku->kategori->nama : 'Umum' }}
                </span>
                <h5 class="fw-bold mb-0 text-dark" style="letter-spacing:-0.02em;">{{ $buku->judul }}</h5>
            </div>
            <span class="text-muted small" style="font-variant-numeric: tabular-nums;">ID #{{ $buku->id }}</span>
        </div>

        <div class="panel-body">
            <div class="row py-2 border-bottom">
                <div class="col-sm-4 text-muted small">Penulis</div>
                <div class="col-sm-8 text-dark fw-medium small">{{ $buku->penulis }}</div>
            </div>
            <div class="row py-2 border-bottom">
                <div class="col-sm-4 text-muted small">Tahun Terbit</div>
                <div class="col-sm-8 text-dark fw-medium small" style="font-variant-numeric: tabular-nums;">{{ $buku->tahun_terbit }}</div>
            </div>
            <div class="row py-2">
                <div class="col-sm-4 text-muted small">Status Ketersediaan</div>
                <div class="col-sm-8 small">
                    <span class="text-success fw-medium">Tersedia di Rak Utama</span>
                </div>
            </div>

            @can('mengajukan peminjaman buku')
            <div class="mt-4 pt-4 border-top d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold small text-dark">Pinjam Buku Ini?</div>
                    <div class="text-muted" style="font-size:0.8125rem;">Pengajuan peminjaman akan diverifikasi oleh petugas.</div>
                </div>
                <form action="{{ route('peminjaman.ajukan') }}" method="POST" class="mb-0">
                    @csrf
                    <input type="hidden" name="buku_id" value="{{ $buku->id }}">
                    <button type="submit" class="btn-brand" onclick="return confirm('Konfirmasi pengajuan peminjaman untuk {{ $buku->judul }}?')">
                        Ajukan Sekarang
                    </button>
                </form>
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection