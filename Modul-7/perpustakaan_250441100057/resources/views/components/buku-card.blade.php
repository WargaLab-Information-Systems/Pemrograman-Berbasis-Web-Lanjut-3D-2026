@props(['buku'])

<div class="buku-card">

    <div class="buku-atas">
        <p class="kategori">
            {{ $buku['kategori'] }}
        </p>

        <h3>
            {{ $buku['judul'] }}
        </h3>
    </div>

    <div class="buku-isi">

        <p>
            <strong>Penulis</strong>
            <br>
            {{ $buku['penulis'] }}
        </p>

        <p>
            <strong>Tahun Terbit</strong>
            <br>
            {{ $buku['tahun'] }}
        </p>

        {{ $slot }}

    </div>

    <div class="buku-footer">
        {{ $footer ?? '' }}
    </div>

</div>