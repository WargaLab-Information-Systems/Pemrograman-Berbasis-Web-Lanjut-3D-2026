@extends('layouts.main')

@section('title', 'Detail Buku - ' . ($buku->judul ?? 'Perpustakaan'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('buku.index') }}" class="inline-flex items-center text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
            &larr; Kembali ke Daftar Buku
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-800 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden p-6 md:p-8">
        <div class="flex flex-col md:flex-row gap-8 items-start">
            <div class="w-full md:w-1/3 flex flex-col items-center justify-center p-8 bg-indigo-50 dark:bg-gray-700 rounded-lg text-center">
                <span class="text-6xl mb-4">📖</span>
                <span class="px-3 py-1 bg-indigo-600 text-white rounded-full text-xs font-semibold">
                    {{ $buku->kategori?->nama ?? 'Umum' }}
                </span>
                <span class="text-xs text-gray-500 dark:text-gray-400 mt-2">ID Buku: #{{ $buku->id }}</span>
            </div>

            <div class="w-full md:w-2/3 space-y-4">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $buku->judul }}</h1>

                <div class="grid grid-cols-2 gap-4 border-t border-b py-4 border-gray-200 dark:border-gray-700">
                    <div>
                        <span class="text-xs text-gray-500 uppercase">Penulis</span>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $buku->penulis }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 uppercase">Tahun Terbit</span>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $buku->tahun_terbit }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 uppercase">Kategori</span>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $buku->kategori?->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 uppercase">Status</span>
                        <p class="text-sm font-semibold text-green-600">Tersedia di Perpustakaan</p>
                    </div>
                </div>

                <div class="pt-4 flex flex-wrap gap-3">
                    @can('mengajukan peminjaman buku')
                        <button type="button" onclick="pinjamBukuDetail({{ $buku->id }})" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow transition">
                            Pinjam Buku Ini
                        </button>
                    @endcan

                    @can('kelola data buku')
                        <a href="{{ route('buku.index') }}" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-lg transition">
                            Kelola di Tabel Buku
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<script>
async function pinjamBukuDetail(bukuId) {
    if (!confirm('Apakah Anda yakin ingin mengajukan peminjaman untuk buku ini?')) return;

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const response = await fetch('/api/peminjaman', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            credentials: 'same-origin',
            body: JSON.stringify({ buku_id: bukuId })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Gagal mengajukan peminjaman.');
        }

        alert(data.message || 'Peminjaman berhasil diajukan!');
        window.location.href = '{{ route("peminjaman.saya") }}';
    } catch (e) {
        alert(e.message);
    }
}
</script>
@endsection
