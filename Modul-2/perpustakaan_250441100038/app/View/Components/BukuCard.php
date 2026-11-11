@props(['buku'])

<div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm flex flex-col justify-between p-5">
    <div>
        <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-1">{{ $buku->judul }}</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Penulis: {{ $buku->penulis }}</p>
        <p class="text-xs text-gray-500">Tahun: {{ $buku->tahun_terbit ?? '-' }}</p>
    </div>
    <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
        <span class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded">
            {{ $buku->kategori->nama ?? 'Umum' }}
        </span>
        <a href="{{ route('buku.show', $buku->id) }}" class="text-sm text-indigo-600 dark:text-indigo-400 font-semibold hover:underline">Lihat &rarr;</a>
    </div>
</div>