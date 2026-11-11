@extends('layouts.main')

@section('title', 'Tambah Pengguna')

@section('content')
    <h2 style="color:#2f7a4c;">Tambah Pengguna</h2>
    <form method="POST" action="{{ route('pengguna.store') }}">
        @csrf
        @include('pengguna._form')
        <button class="tombol" type="submit">Simpan</button>
        <a class="tombol tombol-abu" href="{{ route('pengguna.index') }}">Batal</a>
    </form>
@endsection
