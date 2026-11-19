@if ($buku)

    <div class="detail-card">

        <img
            src="{{ asset($buku['gambar']) }}"
            alt="{{ $buku['judul'] }}"
            class="detail-image"
        >

        <div class="detail-content">

            <h2>{{ $buku['judul'] }}</h2>

            <div class="detail-info">

                <p>
                    <strong>ID Buku:</strong>
                    {{ $buku['id'] }}
                </p>

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

            </div>

            <a
                href="{{ route('buku.index') }}"
                class="button"
            >
                Kembali ke Daftar Buku
            </a>

        </div>

    </div>

@else

    <div class="not-found">

        <h2>Buku Tidak Ditemukan</h2>

        <p>
            Maaf, data buku yang Anda cari tidak tersedia.
        </p>

        <a href="{{ route('buku.index') }}" class="button">
            Kembali ke Daftar Buku
        </a>

    </div>

@endif