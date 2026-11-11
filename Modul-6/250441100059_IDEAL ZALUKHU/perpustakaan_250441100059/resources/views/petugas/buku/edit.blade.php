
<x-petugas-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                    Edit Buku
                </h2>

                <form action="{{ route('petugas.buku.update', $buku->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium mb-2" for="judul">Judul Buku</label>
                        <input id="judul" type="text" name="judul"
                               value="{{ old('judul', $buku->judul) }}"
                               class="w-full border-gray-300 rounded-md"
                               required>
                        @error('judul')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2" for="penulis">Penulis</label>
                        <input id="penulis" type="text" name="penulis"
                               value="{{ old('penulis', $buku->penulis) }}"
                               class="w-full border-gray-300 rounded-md"
                               required>
                        @error('penulis')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2" for="kategori_id">Kategori</label>
                        <select id="kategori_id" name="kategori_id"
                                class="w-full border-gray-300 rounded-md"
                                required>
                            <option value="">-- Pilih Kategori --</option>

                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}"
                                    @selected(old('kategori_id', $buku->kategori_id) == $kategori->id)>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2" for="tahun_terbit">Tahun Terbit</label>
                        <input id="tahun_terbit" type="number" name="tahun_terbit"
                               value="{{ old('tahun_terbit', $buku->tahun_terbit) }}"
                               class="w-full border-gray-300 rounded-md"
                               required>
                        @error('tahun_terbit')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                            Simpan Perubahan
                        </button>

                        <a href="{{ route('petugas.buku.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-petugas-layout>
