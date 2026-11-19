@extends('layouts.dashboard')

@section('title', 'Tambah Buku')

@section('content')
<div class="dashboard-heading">
    <h2>Tambah Buku</h2>
</div>

<form action="{{ route('kelola.buku.store') }}" method="POST" class="user-form">
    @csrf
    @include('kelola.buku._form')

    <div class="form-actions">
        <button type="submit" class="btn-uniform">Simpan</button>
        <a href="{{ route('kelola.buku.index') }}" class="btn-uniform btn-secondary">Kembali</a>
    </div>
</form>
@endsection
