@extends('layouts.dashboard')

@section('title', 'Ubah Buku')

@section('content')
<div class="dashboard-heading">
    <h2>Ubah Buku</h2>
</div>

<form action="{{ route('kelola.buku.update', $buku->id) }}" method="POST" class="user-form">
    @csrf
    @method('PUT')
    @include('kelola.buku._form')

    <div class="form-actions">
        <button type="submit" class="btn-uniform">Simpan Perubahan</button>
        <a href="{{ route('kelola.buku.index') }}" class="btn-uniform btn-secondary">Kembali</a>
    </div>
</form>
@endsection
