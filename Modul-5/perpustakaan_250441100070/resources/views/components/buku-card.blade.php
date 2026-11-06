@props([
    'judul',
    'penulis',
    'tahun',
    'id',
])

<div class="buku-card">
    {{-- 11. Named slot untuk bagian tambahan (kategori) --}}
    @isset($kategori)
        <span class="buku-card__kategori">{{ $kategori }}</span>
    @endisset

    {{-- 10a-c. Judul, penulis, tahun terbit --}}
    <h3 class="buku-card__judul">{{ $judul }}</h3>
    <p class="buku-card__penulis"><strong>Penulis:</strong> {{ $penulis }}</p>
    <p class="buku-card__tahun"><strong>Tahun Terbit:</strong> {{ $tahun }}</p>

    {{-- 10d. Tombol untuk melihat detail buku --}}
    <a href="{{ route('buku.show', $id) }}" class="btn btn-detail">Lihat Detail</a>
</div>
