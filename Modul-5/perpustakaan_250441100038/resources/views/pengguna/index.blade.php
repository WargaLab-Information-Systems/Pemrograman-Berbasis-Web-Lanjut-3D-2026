@extends('layouts.main')

@section('title', 'Kelola Pengguna')

@section('content')
    <h2 style="color:#2f7a4c;">Kelola Pengguna &amp; Role</h2>
    <p><a class="tombol" href="{{ route('pengguna.create') }}">+ Tambah Pengguna</a></p>

    <table class="tbl">
        <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr></thead>
        <tbody>
        @foreach ($pengguna as $u)
            <tr>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->getRoleNames()->implode(', ') ?: '-' }}</td>
                <td>
                    <a class="tombol tombol-kuning" href="{{ route('pengguna.edit', $u) }}">Ubah</a>
                    @if (! $u->is(auth()->user()))
                        <form method="POST" action="{{ route('pengguna.destroy', $u) }}" style="display:inline" onsubmit="return confirm('Hapus pengguna ini?')">
                            @csrf @method('DELETE')
                            <button class="tombol tombol-merah" type="submit">Hapus</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
