{{--
    Komponen Kartu Buku
    Pemakaian:
    <x-kartu-buku :buku="$buku">
        Teks tambahan (default slot)
        <x-slot:kategori>Novel</x-slot:kategori>   (named slot, opsional)
    </x-kartu-buku>
--}}
@props(['buku'])

<article class="kartu-buku">
    {{-- Named slot: label kategori (opsional) --}}
    @isset($kategori)
        <span class="badge">{{ $kategori }}</span>
    @endisset

    <h3 class="kartu-judul">{{ $buku['judul'] }}</h3>
    <p class="kartu-meta">Penulis: {{ $buku['penulis'] }}</p>
    <p class="kartu-meta">Tahun terbit: {{ $buku['tahun_terbit'] }}</p>

    {{-- Default slot: bagian tambahan --}}
    @if (!$slot->isEmpty())
        <div class="kartu-extra">{{ $slot }}</div>
    @endif

    <a href="{{ route('buku.show', $buku['id']) }}" class="btn">Lihat detail</a>
</article>
