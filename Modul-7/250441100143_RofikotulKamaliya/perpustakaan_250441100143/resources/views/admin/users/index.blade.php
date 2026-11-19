
@extends('layouts.dashboard')

@section('title', 'Pengelolaan Pengguna')

@section('content')
<div class="dashboard-heading">
    <h2>Pengelolaan Pengguna</h2>
    <p>Kelola akun dan hak akses pengguna perpustakaan.</p>
</div>

<a href="{{ route('admin.users.create') }}" class="btn-primary">
    + Tambah Pengguna
</a>

<div class="table-container">
    <table>
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
            @forelse($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->getRoleNames()->implode(', ') }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user->id) }}">Edit</a>

                        <form action="{{ route('admin.users.destroy', $user->id) }}"
                              method="POST"
                              style="display:inline"
                              onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Belum ada data pengguna.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
