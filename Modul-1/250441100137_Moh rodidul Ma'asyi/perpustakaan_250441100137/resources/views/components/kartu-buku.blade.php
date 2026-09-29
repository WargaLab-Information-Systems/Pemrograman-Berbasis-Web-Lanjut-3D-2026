@props(['buku'])

<div class="card">
    {{-- Named slot: badge (opsional) --}}
    @isset($badge)
        <span class="badge">{{ $badge }}</span>
    @endisset

    <h3 class="card__title">{{ $buku['judul'] }}</h3>
    <p class="card__text">Penulis: {{ $buku['penulis'] }}</p>
    <p class="card__text">Tahun terbit: {{ $buku['tahun_terbit'] }}</p>

    {{-- Default slot: bagian tambahan (opsional) --}}
    @if (! $slot->isEmpty())
        <div class="card__extra">{{ $slot }}</div>
    @endif

    <a href="{{ route('buku.show', $buku['id']) }}" class="btn">Lihat Detail</a>
</div>
