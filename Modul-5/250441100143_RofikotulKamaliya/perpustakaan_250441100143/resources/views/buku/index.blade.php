@extends('layouts.dashboard')

@section('title', 'Daftar Buku')

@section('content')
<div class="dashboard-heading">
    <h2>Daftar Buku</h2>
    <p>Koleksi buku perpustakaan.</p>
</div>

<form method="GET" action="{{ route('buku.index') }}" class="search-bar">
    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari judul / penulis">
    <button type="submit" class="btn-uniform">Cari</button>
    @can('kelola data buku')
        <a href="{{ route('kelola.buku.index') }}" class="btn-uniform">Kelola Buku</a>
    @endcan
</form>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Kategori</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tahun Terbit</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bukus as $buku)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $buku->kategori->nama ?? '-' }}</td>
                    <td>{{ $buku->judul }}</td>
                    <td>{{ $buku->penulis }}</td>
                    <td>{{ $buku->tahun_terbit }}</td>
                    <td>
                        <a href="{{ route('buku.show', $buku->id) }}">Lihat Detail</a>

                        @can('mengajukan peminjaman buku')
                            <form action="{{ route('peminjaman.store') }}" method="POST" class="inline-form">
                                @csrf
                                <input type="hidden" name="buku_id" value="{{ $buku->id }}">
                                <button type="submit" class="btn-sm">Ajukan Pinjam</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Belum ada data buku.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
