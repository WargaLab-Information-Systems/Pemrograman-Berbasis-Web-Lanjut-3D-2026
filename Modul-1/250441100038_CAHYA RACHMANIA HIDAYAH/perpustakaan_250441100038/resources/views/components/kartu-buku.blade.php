<div class="kartu-buku">
    <div>
        <h3>{{ $judul }}</h3>
        <p><strong>Penulis:</strong> {{ $penulis }}</p>
        <p><strong>Tahun Terbit:</strong> {{ $tahun }}</p>
        
        <div style="margin-top: 10px; font-style: italic; font-size: 13px; color: #0077b6;">
            {{ $slot }}
        </div>
    </div>

    <div>
        <a href="{{ route('buku.show', $id) }}" class="btn-detail">Lihat Detail Buku</a>
    </div>
</div>