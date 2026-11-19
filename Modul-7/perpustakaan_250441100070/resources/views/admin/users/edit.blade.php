@extends('layouts.app')

@section('title', 'Ubah Pengguna - Perpustakaan Digital')

@section('content')
    <h2 class="page-title">Ubah Pengguna</h2>

    <section class="panel">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Nama</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>

                {{-- Penetapan role hanya untuk yang punya permission "kelola role pengguna" --}}
                @can('kelola role pengguna')
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role">
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}"
                                    @selected(old('role', $user->getRoleNames()->first()) === $role->name)>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endcan
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </section>
@endsection
