@props(['book'])
<div {{ $attributes->merge(['class' => 'book-card']) }}>
    <div class="book-card-content">
        <span class="book-category">
            {{ $book['kategori'] }}
        </span>
        <h2>{{ $book['judul'] }}</h2>
        <p class="book-author">
            {{ $book['penulis'] }}
        </p>
        <p class="book-year">
            Tahun Terbit: {{ $book['tahun'] }}
        </p>
        <a href="{{ route('buku.show', $book['id']) }}" class="btn-detail">Lihat Detail</a>
    </div>
    <div class="book-card-footer">
        {{ $footer ?? '' }}
    </div>
</div>