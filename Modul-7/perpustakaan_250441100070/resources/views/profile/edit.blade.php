@extends('layouts.app')

@section('title', 'Profil Saya - Perpustakaan Digital')

@section('content')
    <h2 class="page-title">Profil Saya</h2>

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success" role="status">Profil berhasil diperbarui.</div>
    @endif
    @if (session('status') === 'password-updated')
        <div class="alert alert-success" role="status">Kata sandi berhasil diperbarui.</div>
    @endif

    {{-- Data profil. Selalu milik pengguna yang sedang login. --}}
    <section class="panel">
        <h3>Informasi Profil</h3>

        @can('ubah profil sendiri')
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="no_telepon">No. Telepon</label>
                        <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $anggota->no_telepon) }}">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Simpan Profil</button>
                </div>
            </form>
        @else
            <p><strong>Nama:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>No. Telepon:</strong> {{ $anggota->no_telepon ?? '-' }}</p>
        @endcan
    </section>

    @can('ubah profil sendiri')
        <section class="panel">
            <h3>Ubah Kata Sandi</h3>

            @if ($errors->updatePassword->any())
                <div class="alert alert-danger" role="alert">
                    @foreach ($errors->updatePassword->all() as $pesan)
                        <div>{{ $pesan }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-group">
                        <label for="current_password">Kata sandi saat ini</label>
                        <input type="password" id="current_password" name="current_password" autocomplete="current-password">
                    </div>

                    <div class="form-group">
                        <label for="password">Kata sandi baru</label>
                        <input type="password" id="password" name="password" autocomplete="new-password">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi kata sandi baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Simpan Kata Sandi</button>
                </div>
            </form>
        </section>
    @endcan
@endsection
