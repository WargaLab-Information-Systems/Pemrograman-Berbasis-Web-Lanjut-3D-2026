@extends('layouts.main')

@section('title', 'Daftar Buku - Perpustakaan Digital')

@section('content')
<div class="page-header">
    <h2>Daftar Koleksi Buku</h2>
    <p>Menampilkan seluruh buku yang tersedia di katalog perpustakaan.</p>
</div>

<div class="buku-grid">
    @forelse($buku as $item)
        <x-buku-card 
            :id="$item['id']"
            :judul="$item['judul']"
            :penulis="$item['penulis']"
            :tahunTerbit="$item['tahun_terbit']"
            :kategori="$item['kategori']">
            
            <!-- Menggunakan Named Slot $extraInfo -->
            <x-slot:extraInfo>
                <small class="text-muted">ID Katalog: #BK-{{ str_pad($item['id'], 3, '0', STR_PAD_LEFT) }}</small>
            </x-slot:extraInfo>
            
        </x-buku-card>
    @empty
        <div class="alert alert-warning">
            <p>Belum ada data buku yang tersedia saat ini.</p>
        </div>
    @endforelse
</div>
@endsection