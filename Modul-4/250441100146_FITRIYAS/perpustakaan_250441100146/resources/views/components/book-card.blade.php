<div class="book-card">

    <div class="book-icon">
        📖
    </div>

    <div class="book-info">

        <h2>{{ $judul }}</h2>

        <p>
            <strong>Penulis:</strong>
            {{ $penulis }}
        </p>

        <p>
            <strong>Tahun Terbit:</strong>
            {{ $tahun }}
        </p>

        <a href="{{ $link }}" class="btn">
            Lihat Detail
        </a>

        @if ($slot->isNotEmpty())
            <div class="book-extra">
                {{ $slot }}
            </div>
        @endif

    </div>

</div>