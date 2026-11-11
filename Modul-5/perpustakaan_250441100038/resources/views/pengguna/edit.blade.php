@extends('layouts.main')

@section('title', 'Ubah Pengguna')

@section('content')
    <h2 style="color:#2f7a4c;">Ubah Pengguna</h2>
    <form method="POST" action="{{ route('pengguna.update', $pengguna) }}">
        @csrf @method('PUT')
        @include('pengguna._form')
        <button class="tombol" type="submit">Perbarui</button>
        <a class="tombol tombol-abu" href="{{ route('pengguna.index') }}">Batal</a>
    </form>
@endsection
