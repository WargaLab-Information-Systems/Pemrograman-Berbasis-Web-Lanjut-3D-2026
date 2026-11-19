@extends('layouts.app')

@section('judul', 'Detail Buku')

@section('konten')
    @if ($buku)
        <article class="detail-buku">
            <a class="detail-kembali" href="{{ route('buku.index') }}">Kembali ke daftar buku</a>
            <p class="detail-kategori">{{ $buku->kategoriRelasi?->nama ?? ($buku['kategori'] ?? '-') }}</p>
            <h2>{{ $buku->judul ?? $buku['judul'] }}</h2>
            <p class="detail-penulis">Penulis: {{ $buku->penulis ?? $buku['penulis'] }}</p>

            <dl class="detail-meta">
                <div>
                    <dt>Tahun terbit</dt>
                    <dd>{{ $buku->tahun_terbit ?? $buku['tahun_terbit'] }}</dd>
                </div>
            </dl>

            <div style="margin-top: 24px;">
                @auth
                    @can('mengajukan peminjaman buku')
                        <form action="{{ route('buku.pinjam', $buku->id ?? $buku['id']) }}" method="POST">
                            @csrf
                            <button type="submit" class="tombol-utama" onclick="return confirm('Ajukan peminjaman buku ini?')">
                                Pinjam Buku Ini
                            </button>
                        </form>
                    @endcan
                @else
                    <p style="font-size: 13px; color: var(--muted); margin-top: 10px;">
                        Silakan <a href="{{ route('login') }}" style="color: var(--rose); font-weight: 600; text-decoration: underline;">login</a> terlebih dahulu untuk meminjam buku ini.
                    </p>
                @endauth
            </div>
        </article>
    @else
        <section class="detail-tidak-ditemukan">
            <h2>Buku Tidak Ditemukan</h2>
            <p>Data buku dengan ID tersebut tidak tersedia.</p>
            <a class="detail-kembali" href="{{ route('buku.index') }}">Kembali ke daftar buku</a>
        </section>
    @endif
@endsection
