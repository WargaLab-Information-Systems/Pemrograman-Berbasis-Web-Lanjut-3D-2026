@props(['id', 'judul', 'penulis', 'tahun'])

<div class="buku-card">
    <div>
        <h3>{{ $judul }}</h3>
        <p><strong>Penulis:</strong> {{ $penulis }}</p>
        <p><strong>Tahun Terbit:</strong> {{ $tahun }}</p>

        <div class="badge-kategori">
            {{ $slot }}
        </div>
    </div>

    <div>
        <a href="{{ route('buku.show', $id) }}" class="btn-detail">Lihat Detail</a>
    </div>
</div>