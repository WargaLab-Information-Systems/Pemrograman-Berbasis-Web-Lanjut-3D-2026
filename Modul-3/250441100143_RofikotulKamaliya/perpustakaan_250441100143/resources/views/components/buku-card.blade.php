<div class="book-card">

    <div class="book-card-body">

        <h3>{{ $judul }}</h3>

        <p>
            <strong>Penulis:</strong>
            {{ $penulis }}
        </p>

        <p>
            <strong>Tahun Terbit:</strong>
            {{ $tahun }}
        </p>

        {{ $slot }}

    </div>

    <div class="book-card-footer">

        <a href="{{ route('buku.show', $id) }}" class="btn">
            Lihat Detail
        </a>

    </div>

</div>