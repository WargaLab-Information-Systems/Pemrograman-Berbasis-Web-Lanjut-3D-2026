@extends('layouts.dashboard')

@section('title', 'Kelola Buku')

@section('content')
<div class="dashboard-heading">
    <h2>Kelola Data Buku</h2>
    <p>Tambah, ubah, dan hapus data buku perpustakaan.</p>
</div>

<a href="{{ route('kelola.buku.create') }}" class="btn-primary">+ Tambah Buku</a>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tahun</th>
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
                        <a href="{{ route('kelola.buku.edit', $buku->id) }}">Ubah</a>

                        <form action="{{ route('kelola.buku.destroy', $buku->id) }}" method="POST"
                              class="inline-form"
                              onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Belum ada data buku.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
