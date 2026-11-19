@extends('layouts.dashboard')

@section('title', 'Peminjaman Saya')

@section('content')
<div class="dashboard-heading">
    <h2>Peminjaman Saya</h2>
    <p>Status pengajuan dan peminjaman yang sedang berjalan.</p>
</div>

@include('peminjaman._tabel')
@endsection
