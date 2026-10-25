<div class="buku-card">

    <h2>{{ $judul }}</h2>

    <p>
        <strong>Penulis :</strong> {{ $penulis }}
    </p>

    <p>
        <strong>Tahun Terbit:</strong> {{ $tahun }}
    </p>

    {{ $slot }}

    <a href="{{ route('buku.show', $id) }}">
        Lihat Detail
    </a>
    
</div>