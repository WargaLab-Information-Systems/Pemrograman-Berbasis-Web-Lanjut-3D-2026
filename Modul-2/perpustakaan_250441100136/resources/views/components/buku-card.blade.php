@props(['id', 'judul', 'penulis', 'tahunTerbit', 'deskripsi' => null, 'kategori'])

<div class="card-buku">
    <div class="card-header">
        <span class="badge">{{ $kategori }}</span>
    </div>
    <div class="card-body">
        <h3 class="card-title">{{ $judul }}</h3>
        <p class="card-author"><strong>Penulis:</strong> {{ $penulis }}</p>
        <p class="card-year"><strong>Tahun:</strong> {{ $tahunTerbit }}</p>
        @if ($deskripsi)
            <p class="card-description"><strong>Deskripsi:</strong> {{ \Illuminate\Support\Str::limit($deskripsi, 100) }}</p>
        @endif
        
        <!-- Named Slot atau Slot opsional -->
        @if (isset($extraInfo))
            <div class="card-extra">
                {{ $extraInfo }}
            </div>
        @endif
    </div>
    <div class="card-footer">
        <a href="{{ route('buku.show', $id) }}" class="btn btn-primary">Lihat Detail</a>
    </div>
</div>