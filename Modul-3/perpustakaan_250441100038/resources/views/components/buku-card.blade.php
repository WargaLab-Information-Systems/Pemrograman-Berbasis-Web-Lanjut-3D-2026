@props(['buku'])

<div class="card">
    @isset($badge)
        <span class="badge">{{ $badge }}</span>
    @endisset

    <h3 class="card-title">{{ $buku['judul'] }}</h3>
    <p class="card-text">Penulis: {{ $buku['penulis'] }}</p>
    <p class="card-text">Tahun Terbit: {{ $buku['tahun_terbit'] }}</p>

    <a href="{{ route('buku.show', $buku['id']) }}" class="btn">Lihat Detail</a>

    {{ $slot }}
</div>