<div class="book-card">

    <div class="book-icon">
        📖
    </div>

    <span class="book-category">
        {{ $kategori }}
    </span>

    <h3>
        {{ $judul }}
    </h3>

    <p class="book-author">
        Penulis: {{ $penulis }}
    </p>

    <p class="book-year">
        Tahun Terbit: {{ $tahun }}
    </p>

    <div class="book-button">
        {{ $slot }}
    </div>

</div>