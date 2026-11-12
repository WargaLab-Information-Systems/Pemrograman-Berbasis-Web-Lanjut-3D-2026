
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Peminjaman Buku
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('petugas.peminjaman.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="anggota_id" class="block font-medium text-sm text-gray-700">
                            Nama Anggota
                        </label>

                        <select name="anggota_id" id="anggota_id"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">-- Pilih Anggota --</option>

                            @foreach ($anggotas as $anggota)
                                <option value="{{ $anggota->id }}">
                                    {{ $anggota->nama }}
                                </option>
                            @endforeach
                        </select>

                        @error('anggota_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="buku_id" class="block font-medium text-sm text-gray-700">
                            Judul Buku
                        </label>

                        <select name="buku_id" id="buku_id"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">-- Pilih Buku --</option>

                            @foreach ($bukus as $buku)
                                <option value="{{ $buku->id }}">
                                    {{ $buku->judul }}
                                </option>
                            @endforeach
                        </select>

                        @error('buku_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="tanggal_pinjam" class="block font-medium text-sm text-gray-700">
                            Tanggal Pinjam
                        </label>

                        <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                            value="{{ date('Y-m-d') }}"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>

                        @error('tanggal_pinjam')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            Simpan Peminjaman
                        </button>

                        <a href="{{ route('petugas.peminjaman.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                            Kembali
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
