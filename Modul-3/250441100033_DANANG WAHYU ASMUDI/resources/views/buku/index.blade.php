@extends("layouts.app")

@section("content")
    <h2>Daftar Koleksi Buku</h2>

    <div class="grid-buku">
        @foreach ($bukuList as $buku)
            <x-card-buku :buku="$buku">
                <x-slot:badgeSlot>
                    <span class="badge">{{ $buku["kategori"] }}</span>
                </x-slot:badgeSlot>
            </x-card-buku>
        @endforeach
    </div>
@endsection