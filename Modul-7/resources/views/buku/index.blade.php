@extends('layouts.main')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert border-0 rounded-2 py-2 px-3 mb-3 d-flex justify-content-between align-items-center" style="background:#f0fdf4; border:1px solid #bbf7d0 !important; color:#166534; font-size:0.8125rem;">
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close shadow-none" style="font-size:0.65rem;" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="letter-spacing: -0.02em;">Katalog Koleksi Buku</h4>
            <p class="text-muted small mb-0">Daftar buku literatur yang terdaftar pada pangkalan data perpustakaan.</p>
        </div>
        @can('kelola data buku')
            <a href="{{ route('buku.create') }}" class="btn-brand text-decoration-none">+ Tambah Buku</a>
        @endcan
    </div>

    <div class="panel">
        <div class="table-responsive">
            <table class="table-minimal">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th style="width: 140px;">Kategori</th>
                        <th>Judul Buku</th>
                        <th style="width: 200px;">Penulis</th>
                        <th style="width: 100px;" class="text-center">Tahun</th>
                        <th style="width: 240px;" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bukus as $index => $buku)
                    <tr>
                        <td class="text-center text-muted" style="font-size:0.8125rem;">{{ $index + 1 }}</td>
                        <td>
                            <span class="badge border bg-light text-secondary fw-normal px-2 py-1" style="font-size:0.75rem;">
                                {{ $buku->kategori ? $buku->kategori->nama : 'Umum' }}
                            </span>
                        </td>
                        <td class="fw-semibold text-dark">{{ $buku->judul }}</td>
                        <td class="text-secondary small">{{ $buku->penulis }}</td>
                        <td class="text-center text-secondary small">{{ $buku->tahun_terbit }}</td>
                        <td class="text-end">
                            <div class="d-inline-flex align-items-center gap-1">
                                <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-sm btn-subtle py-1 px-2" style="font-size:0.75rem;">Detail</a>
                                @can('kelola data buku')
                                    <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-sm btn-subtle py-1 px-2" style="font-size:0.75rem;">Ubah</a>
                                    <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" class="d-inline mb-0" onsubmit="return confirm('Hapus buku ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action-danger">Hapus</button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted small">Belum ada data buku.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection