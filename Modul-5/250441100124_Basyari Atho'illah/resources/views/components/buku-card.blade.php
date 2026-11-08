@props(['book'])

<article class="buku-card">

    {{-- Header Card --}}
    <div class="buku-card__header">

        <div class="buku-card__icon">
            <i class="fa-solid fa-book"></i>
        </div>

        <span class="buku-card__badge">
            {{ $book['kategori'] }}
        </span>

    </div>


    {{-- Content --}}
    <div class="buku-card__body">

        <h3 class="buku-card__title">
            {{ $book['judul'] }}
        </h3>

        <div class="buku-card__meta">

            <p class="buku-card__meta-item">
                <i class="fa-solid fa-user"></i>
                <span>{{ $book['penulis'] }}</span>
            </p>

            <p class="buku-card__meta-item">
                <i class="fa-solid fa-calendar"></i>
                <span>{{ $book['tahun'] }}</span>
            </p>

        </div>


        {{-- Slot: Tombol / Konten tambahan dari parent --}}
        @if ($slot->isNotEmpty())
            <div class="buku-card__action">
                {{ $slot }}
            </div>
        @endif

    </div>

</article>