@extends('layouts.dashboard')

@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="dashboard-heading">
    <h2>Riwayat Peminjaman Saya</h2>
    <p>Seluruh riwayat peminjaman milik Anda.</p>
</div>

@include('peminjaman._tabel')
@endsection
