@extends('layouts.main')

@section('content')
<div class="container" style="max-width: 600px;">
    <div class="panel">
        <div class="panel-header"><h6 class="mb-0 fw-bold">Ubah Buku #{{ $buku->id }}</h6></div>
        <div class="panel-body">
            <form action="{{ route('buku.update', $buku->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-select" required>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" {{ $buku->kategori_id == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3"><label class="form-label">Judul Buku</label><input type="text" name="judul" class="form-control" value="{{ $buku->judul }}" required></div>
                <div class="mb-3"><label class="form-label">Penulis</label><input type="text" name="penulis" class="form-control" value="{{ $buku->penulis }}" required></div>
                <div class="mb-3"><label class="form-label">Tahun Terbit</label><input type="number" name="tahun_terbit" class="form-control" value="{{ $buku->tahun_terbit }}" required></div>
                <button type="submit" class="btn-brand">Simpan Perubahan</button>
                <a href="{{ route('buku.index') }}" class="btn-subtle text-decoration-none">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection