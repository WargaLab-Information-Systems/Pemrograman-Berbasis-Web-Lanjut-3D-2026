@props(['buku'])

<div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-full border-l-4 border-l-orange-400 overflow-hidden hover:shadow-md transition-shadow">
    <!-- Bagian Atas: Info Buku -->
    <div class="p-6 flex-grow">
        <h3 class="text-xl font-bold text-gray-900 mb-4">{{ $buku->judul }}</h3>
        
        <p class="text-sm text-gray-500 mb-1">Penulis: {{ $buku->penulis }}</p>
        <p class="text-sm text-gray-500 mb-5">Tahun Terbit: {{ $buku->tahun_terbit ?? $buku->tahun }}</p>
        
        <!-- Badge Kategori Kuning -->
        <span class="inline-block bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded-md font-semibold">
            {{ $buku->kategori->nama ?? $buku->kategori ?? 'Kategori' }}
        </span>
    </div>
    
    <!-- Bagian Bawah: Tombol Lihat Detail -->
    <div class="px-6 pb-6 mt-auto">
        <!-- Tautan ini akan mengarahkan tombol ke halaman detail -->
        <a href="{{ route('buku.show', $buku->id) }}" 
           class="block w-full bg-[#1e293b] hover:bg-black text-white text-center py-3 rounded-lg text-sm font-medium transition-colors">
            Lihat Detail
        </a>
    </div>
</div>