@props(['judul', 'penulis', 'tahun', 'linkDetail'])
<div class="kartu">
    <h3>{{ $judul }}</h3>
    <p>Penulis: {{ $penulis }}</p>
    <p>Tahun Terbit: {{ $tahun }}</p>
    
    <div style="margin: 10px 0;">
        {{ $slot }}
    </div>

    <a href="{{ $linkDetail }}" class="btn">Lihat Detail Buku</a>
</div>