@extends('layouts.main')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Profil Saya (ID Pengguna: {{ $user->id }})</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role Akun</label>
                    <input type="text" class="form-control bg-light" value="{{ strtoupper($user->getRoleNames()->first() ?? 'Siswa') }}" readonly>
                </div>
                @can('ubah profil sendiri')
                    <button type="submit" class="btn btn-primary">Perbarui Profil</button>
                @endcan
            </form>
        </div>
    </div>
</div>
@endsection