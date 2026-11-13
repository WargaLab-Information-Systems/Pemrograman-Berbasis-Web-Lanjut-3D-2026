@props(['id', 'judul', 'penulis', 'tahun'])

{{-- Memakai class dari app.css (.card, .btn) supaya tampilannya sama dengan kotak di beranda --}}
<div class="card">
    <h3 class="card__title">{{ $judul }}</h3>
    <p class="card__text">Penulis: {{ $penulis }}</p>
    <p class="card__text">Tahun terbit: {{ $tahun }}</p>

    {{-- Slot: isi tambahan dari view (mis. kategori) --}}
    @if (! $slot->isEmpty())
        <div class="card__extra">{{ $slot }}</div>
    @endif

    <a href="{{ route('buku.show', $id) }}" class="btn">Lihat Detail</a>
</div>