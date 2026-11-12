<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku Baru - Perpustakaan Digital UTM</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col justify-between">

    <div>
        <!-- Header -->
        <header class="bg-[#111827] text-white shadow-md">
            <div class="max-w-6xl mx-auto px-6 py-4 flex flex-wrap justify-between items-center gap-4">
                <a href="/" class="text-2xl font-bold block hover:text-gray-200">Perpustakaan Digital UTM</a>
                <nav class="flex items-center gap-4 text-sm text-gray-300">
                    <a href="{{ route('buku.index') }}" class="hover:text-white">&larr; Kembali ke Daftar Buku</a>
                </nav>
            </div>
        </header>

        <!-- Form Tambah Buku -->
        <main class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md mt-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Tambah Buku Baru</h1>
            <p class="text-gray-600 text-sm mb-6">Formulir khusus Admin dan Petugas untuk mendaftarkan buku baru ke katalog perpustakaan.</p>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded text-sm font-medium mb-4">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('buku.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700">Kategori Buku</label>
                    <select name="kategori_id" required class="mt-1 block w-full rounded border-gray-300 shadow-sm p-2 border bg-white text-sm">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama ?? $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Judul Buku</label>
                    <input type="text" name="judul" value="{{ old('judul') }}" required placeholder="Contoh: Laskar Pelangi" class="mt-1 block w-full rounded border-gray-300 shadow-sm p-2 border text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Penulis</label>
                    <input type="text" name="penulis" value="{{ old('penulis') }}" required placeholder="Contoh: Andrea Hirata" class="mt-1 block w-full rounded border-gray-300 shadow-sm p-2 border text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', date('Y')) }}" required min="1800" max="2100" class="mt-1 block w-full rounded border-gray-300 shadow-sm p-2 border text-sm">
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('buku.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm transition">
                        Batal
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium transition">
                        Simpan Buku
                    </button>
                </div>
            </form>
        </main>
    </div>

    <footer class="bg-[#111827] text-white text-center py-4 mt-8">
        <p class="text-sm">&copy; 2026 Perpustakaan Digital UTM. All rights reserved.</p>
    </footer>

</body>
</html>
