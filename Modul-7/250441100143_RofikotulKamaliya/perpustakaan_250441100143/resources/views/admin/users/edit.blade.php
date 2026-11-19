@extends('layouts.dashboard')

@section('title', 'Edit Pengguna')

@section('content')
<div class="dashboard-heading">
    <h2>Edit Pengguna</h2>
</div>

<form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="user-form">
    @csrf
    @method('PUT')

    <div class="form-grid">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="form-group">
            <label>Role</label>
            <select name="role" required>
                @foreach (['admin', 'petugas', 'siswa'] as $role)
                    <option value="{{ $role }}"
                        {{ old('role', $user->getRoleNames()->first()) === $role ? 'selected' : '' }}>
                        {{ ucfirst($role) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn-uniform">Simpan Perubahan</button>
        <a href="{{ route('admin.users.index') }}" class="btn-uniform btn-secondary">Kembali</a>
    </div>
</form>
@endsection