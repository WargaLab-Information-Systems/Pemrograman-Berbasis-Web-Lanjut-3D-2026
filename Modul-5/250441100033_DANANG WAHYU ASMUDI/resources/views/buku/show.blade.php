<x-app-layout>
    <div class="bg-white p-6 rounded-lg shadow-md max-w-2xl mx-auto">
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded text-sm font-medium mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if(!$buku)
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <h3 class="font-bold">Data Buku Tidak Ditemukan!</h3>
                <p class="text-sm">Maaf, buku dengan ID tersebut tidak ada dalam sistem kami.</p>
            </div>
            <a href="{{ route('buku.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded text-sm hover:bg-gray-700 inline-block">
                &larr; Kembali ke Daftar Buku
            </a>
        @else
            <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $buku->judul }}</h2>
            <hr class="my-4 border-gray-200">

            <div class="space-y-2 text-gray-700 text-sm">
                <p><strong>ID Buku:</strong> {{ $buku->id }}</p>
                <p><strong>Penulis:</strong> {{ $buku->penulis }}</p>
                <p><strong>Tahun Terbit:</strong> {{ $buku->tahun_terbit }}</p>
                <p><strong>Kategori:</strong>
                    <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs">
                        {{ $buku->kategori->nama ?? $buku->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                    </span>
                </p>
            </div>

            <div class="mt-6 flex flex-wrap justify-between items-center gap-3">
                <a href="{{ route('buku.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded text-sm hover:bg-gray-700 inline-block transition">
                    &larr; Kembali ke Daftar Buku
                </a>

                @auth
                    @php
                        $anggotaUser = \App\Models\Anggota::where('email', Auth::user()->email)->first();
                        $pinjamAktif = $anggotaUser ? \App\Models\Peminjaman::where('anggota_id', $anggotaUser->id)
                            ->where('buku_id', $buku->id)
                            ->whereNull('tanggal_kembali')
                            ->whereNotIn('status', ['ditolak', 'kembali'])
                            ->first() : null;
                    @endphp

                    @if($pinjamAktif)
                        @if(($pinjamAktif->status ?? 'dipinjam') === 'menunggu')
                            <span class="bg-orange-100 border border-orange-300 text-orange-800 px-3 py-1.5 rounded text-sm font-medium">
                                Menunggu Persetujuan Admin/Petugas
                            </span>
                        @else
                            <span class="bg-amber-100 border border-amber-300 text-amber-800 px-3 py-1.5 rounded text-sm font-medium">
                                Buku Ini Sedang Anda Pinjam (Belum Dikembalikan)
                            </span>
                        @endif
                    @else
                        <form action="{{ route('buku.pinjam', $buku->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" onclick="return confirm('Apakah Anda ingin meminjam buku ini?')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded text-sm font-medium transition">
                                Pinjam Buku Ini
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">
                        Login untuk meminjam buku ini &rarr;
                    </a>
                @endauth
            </div>
        @endif
    </div>
</x-app-layout>
