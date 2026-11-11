@extends('layouts.master') <!-- atau app.blade.php, sesuaikan dengan nama layout Anda -->

@section('title', 'Daftar Buku')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Daftar Koleksi Buku</h2>
        <p class="text-gray-600">Berikut adalah daftar lengkap buku perpustakaan saat ini.</p>
    </div>
    
    <!-- Grid dibungkus di sini, bukan di dalam komponen -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($bukus as $buku)
            <!-- Memanggil file kartu-buku.blade.php dan mengirimkan data $buku -->
            <x-kartu-buku :buku="$buku" />
        @endforeach
    </div>
@endsection