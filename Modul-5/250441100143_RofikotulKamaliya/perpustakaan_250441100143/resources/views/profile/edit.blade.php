@extends('layouts.dashboard')

@section('title', 'Profil Saya')

@section('content')
<div class="dashboard-heading">
    <h2>Profil Saya</h2>
    <p>Perbarui data akun Anda. Role: <strong>{{ $user->getRoleNames()->implode(', ') ?: '-' }}</strong></p>
</div>

@if (session('status') === 'profile-updated')
    <p class="alert-success">Profil berhasil diperbarui.</p>
@endif

@if (session('status') === 'password-updated')
    <p class="alert-success">Password berhasil diperbarui.</p>
@endif

<form action="{{ route('profile.update') }}" method="POST" class="user-form">
    @csrf
    @method('PATCH')

    <h3>Informasi Profil</h3>

    <label for="name">Nama</label>
    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name">

    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">

    <div class="form-actions">
        <button type="submit" class="btn-uniform">Simpan Profil</button>
    </div>
</form>

<form action="{{ route('password.update') }}" method="POST" class="user-form">
    @csrf
    @method('PUT')

    <h3>Ubah Password</h3>

    <label for="current_password">Password Saat Ini</label>
    <input type="password" id="current_password" name="current_password" autocomplete="current-password">
    @foreach ($errors->updatePassword->get('current_password') as $pesan)
        <p class="alert-error">{{ $pesan }}</p>
    @endforeach

    <label for="password">Password Baru</label>
    <input type="password" id="password" name="password" autocomplete="new-password">
    @foreach ($errors->updatePassword->get('password') as $pesan)
        <p class="alert-error">{{ $pesan }}</p>
    @endforeach

    <label for="password_confirmation">Konfirmasi Password Baru</label>
    <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password">

    <div class="form-actions">
        <button type="submit" class="btn-uniform">Simpan Password</button>
    </div>
</form>
@endsection
