<div class="book-card">

    <img
        src="{{ asset($gambar) }}"
        alt="{{ $judul }}"
    >

    <div class="book-content">

        <h3>{{ $judul }}</h3>

        <p>
            <strong>Penulis:</strong>
            {{ $penulis }}
        </p>

        <p>
            <strong>Tahun:</strong>
            {{ $tahun }}
        </p>

        {{ $slot }}

    </div>

</div>