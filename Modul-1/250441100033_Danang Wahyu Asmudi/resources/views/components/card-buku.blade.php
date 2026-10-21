@props(['buku'])

<div class="card">
    <!-- Poin 10: Judul, Penulis, Tahun Terbit -->
    <h3 class="card-title">{{ $buku['judul'] }}</h3>
    <p><strong>Penulis:</strong> {{ $buku['penulis'] }}</p>
    <p><strong>Tahun Terbit:</strong> {{ $buku['tahun_terbit'] }}</p>

    <!-- Poin 11: Named Slot untuk informasi tambahan -->
    @if (isset($badgeSlot))
        <div style="margin-bottom: 10px;">
            {{ $badgeSlot }}
        </div>
    @endif

    <!-- Poin 10: Tombol Detail -->
    <a href="{{ route('buku.show', $buku['id']) }}" class="btn">Lihat Detail</a>
</div>