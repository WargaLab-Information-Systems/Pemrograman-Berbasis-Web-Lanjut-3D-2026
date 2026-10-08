@props(['buku'])

<div class="book-card">

    <div class="book-icon">
        📖
    </div>

    <div class="book-info">

        <span class="category">
            {{ $buku['kategori'] }}
        </span>

        <h3>
            {{ $buku['judul'] }}
        </h3>

        <p>
            <strong>Penulis:</strong>
            {{ $buku['penulis'] }}
        </p>

        <p>
            <strong>Tahun:</strong>
            {{ $buku['tahun_terbit'] }}
        </p>

        <div class="card-action">

            {{ $footer ?? '' }}

        </div>

    </div>

</div>