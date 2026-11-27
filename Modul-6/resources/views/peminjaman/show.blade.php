@extends('layouts.main')

@section('content')
<div class="container" style="max-width: 680px;">
    <div class="panel">
        <div class="panel-header d-flex justify-content-between align-items-center py-3">
            <h6 class="mb-0 fw-bold">Detail Peminjaman #{{ $peminjaman->id }}</h6>
            <span class="status-pill status-{{ $peminjaman->status }}">{{ strtoupper($peminjaman->status) }}</span>
        </div>
        <div class="panel-body p-4">
            <div class="row py-2 border-bottom">
                <div class="col-sm-4 text-muted small">Nama Peminjam</div>
                <div class="col-sm-8 small fw-semibold">{{ $peminjaman->user->name }} ({{ $peminjaman->user->email }})</div>
            </div>
            <div class="row py-2 border-bottom">
                <div class="col-sm-4 text-muted small">Judul Buku</div>
                <div class="col-sm-8 small">{{ $peminjaman->buku->judul }}</div>
            </div>
            <div class="row py-2 border-bottom">
                <div class="col-sm-4 text-muted small">Tanggal Pinjam</div>
                <div class="col-sm-8 small">{{ $peminjaman->tanggal_pinjam }}</div>
            </div>
            <div class="row py-2">
                <div class="col-sm-4 text-muted small">Tanggal Kembali</div>
                <div class="col-sm-8 small">{{ $peminjaman->tanggal_kembali ?? '—' }}</div>
            </div>
        </div>
    </div>
</div>
@endsection