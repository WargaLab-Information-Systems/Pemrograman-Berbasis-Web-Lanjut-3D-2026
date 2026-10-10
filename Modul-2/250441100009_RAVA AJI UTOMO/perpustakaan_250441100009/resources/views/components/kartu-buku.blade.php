@props([
    'judul' => 'Tanpa Judul',
    'penulis' => '-',
    'tahun'   => '-',
    'kategori' => '-',
    'url' => '#',
])

<article class="baris-buku">
    <div class="baris-judul">
        <h3>{{ $judul }}</h3>
        <p>Penulis: {{ $penulis }}</p>
    </div>
    <div class="baris-data">
        <span>{{ $kategori }}</span>
        <span>{{ $tahun }}</span>
    </div>
    <a class="baris-tautan" href="{{ $url }}">Lihat detail</a>
</article>