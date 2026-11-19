@extends('layouts.app')

@section('title', 'Kelola Pengguna - Perpustakaan Digital')

@section('content')
    <h2 class="page-title">Kelola Pengguna</h2>

    <div class="table-wrap">
        <table class="tabel-buku">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $u)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>
                            @forelse ($u->roles as $role)
                                <span class="badge badge-role">{{ $role->name }}</span>
                            @empty
                                -
                            @endforelse
                        </td>
                        <td class="aksi">
                            <a href="{{ route('riwayat.show', $u) }}" class="btn btn-small btn-secondary">Riwayat</a>
                            <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-small btn-warning">Ubah</a>

                            @if (! auth()->user()->is($u))
                                <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="inline-form"
                                      onsubmit="return confirm('Hapus pengguna {{ $u->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-small btn-danger">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
