@extends('layouts.dashboard')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="dashboard-heading">
    <h2>Tambah Pengguna</h2>
</div>

<form action="{{ route('admin.users.store') }}" method="POST" class="user-form">
    @csrf

    <div class="form-grid">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required>
        </div>

        <div class="form-group">
            <label>Role</label>
            <select name="role" required>
                <option value="">Pilih Role</option>
                @foreach (['admin' => 'Admin', 'petugas' => 'Petugas', 'siswa' => 'Siswa'] as $nilai => $teks)
                    <option value="{{ $nilai }}" {{ old('role') === $nilai ? 'selected' : '' }}>{{ $teks }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn-uniform">Simpan</button>
        <a href="{{ route('admin.users.index') }}" class="btn-uniform btn-secondary">Kembali</a>
    </div>
</form>
@endsection