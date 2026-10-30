@props(['buku'])

<div class="book-content">
    <h3>{{ $buku['judul'] }}</h3>
    {{-- ... --}}
    {{ $slot }}
</div>

<div class="book-card">

    <div class="book-icon">
        📖
    </div>

    <div class="book-content">

        <h3>{{ $buku['judul'] }}</h3>

        <p>
            <strong>Penulis:</strong>
            {{ $buku['penulis'] }}
        </p>

        <p>
            <strong>Tahun Terbit:</strong>
            {{ $buku['tahun'] }}
        </p>

        <p>
            <strong>Kategori:</strong>
            {{ $buku['kategori'] }}
        </p>

        {{-- Named/Default Slot --}}
        <div class="slot-content">
            {{ $slot }}
        </div>

        <a href="{{ route('buku.show', $buku['id']) }}"
           class="btn-detail">
            Lihat Detail
        </a>

    </div>

</div>