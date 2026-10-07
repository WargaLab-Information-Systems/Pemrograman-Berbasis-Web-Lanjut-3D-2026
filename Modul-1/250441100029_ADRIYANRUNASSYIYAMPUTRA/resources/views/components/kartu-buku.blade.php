@props(['judul', 'penulis', 'tahun', 'url'])

<div class="card">
    {{-- Named slot: kategori (opsional) --}}
    @isset($kategori)
        <span class="badge">{{ $kategori }}</span>
    @endisset

    <h3 class="card__title">{{ $judul }}</h3>
    <p class="card__meta"> {{ $penulis }}</p>
    <p class="card__meta"> {{ $tahun }}</p>

    {{-- Default slot: konten tambahan (opsional) --}}
    @if (!$slot->isEmpty())
        <div class="card__extra">{{ $slot }}</div>
    @endif

    <a href="{{ $url }}" class="btn">Lihat Detail</a>
</div>