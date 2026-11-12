
<x-petugas-layout>

    <!-- JUDUL HALAMAN -->
    <div class="admin-welcome">
        <h1 class="admin-page-title">
            Manajemen Buku
        </h1>

        <p>
            Kelola data buku perpustakaan melalui halaman ini.
            Anda dapat menambahkan, mengedit, dan menghapus data buku.
        </p>
    </div>

    @if ($bukuEdit)
        <div class="admin-information" style="margin-top: 0; margin-bottom: 20px;">
            <h2 class="admin-section-title">Formulir Edit Buku</h2>
            <form action="{{ route('petugas.buku.update', $bukuEdit) }}" method="POST" class="admin-inline-form">
                @csrf
                @method('PUT')
                <div class="admin-form-grid">
                    <div><label for="judul">Judul Buku</label><input id="judul" name="judul" value="{{ old('judul', $bukuEdit->judul) }}" required>@error('judul')<p class="form-error">{{ $message }}</p>@enderror</div>
                    <div><label for="penulis">Penulis</label><input id="penulis" name="penulis" value="{{ old('penulis', $bukuEdit->penulis) }}" required>@error('penulis')<p class="form-error">{{ $message }}</p>@enderror</div>
                    <div><label for="kategori_id">Kategori</label><select id="kategori_id" name="kategori_id" required><option value="">Pilih Kategori</option>@foreach ($kategoris as $kategori)<option value="{{ $kategori->id }}" @selected(old('kategori_id', $bukuEdit->kategori_id) == $kategori->id)>{{ $kategori->nama }}</option>@endforeach</select>@error('kategori_id')<p class="form-error">{{ $message }}</p>@enderror</div>
                    <div><label for="tahun_terbit">Tahun Terbit</label><input id="tahun_terbit" name="tahun_terbit" type="number" value="{{ old('tahun_terbit', $bukuEdit->tahun_terbit) }}" required>@error('tahun_terbit')<p class="form-error">{{ $message }}</p>@enderror</div>
                </div>
                <div class="admin-form-actions"><button type="submit" class="admin-form-submit">Simpan Perubahan</button><a href="{{ route('petugas.buku.index') }}" class="admin-form-back">Kembali</a></div>
            </form>
        </div>
    @elseif (request()->boolean('tambah') || $errors->any())
        <div class="admin-information" style="margin-top: 0; margin-bottom: 20px;">
            <h2 class="admin-section-title">Formulir Tambah Buku</h2>
            <form action="{{ route('petugas.buku.store') }}" method="POST" class="admin-inline-form">
                @csrf
                <div class="admin-form-grid">
                    <div><label for="judul">Judul Buku</label><input id="judul" name="judul" value="{{ old('judul') }}" required>@error('judul')<p class="form-error">{{ $message }}</p>@enderror</div>
                    <div><label for="penulis">Penulis</label><input id="penulis" name="penulis" value="{{ old('penulis') }}" required>@error('penulis')<p class="form-error">{{ $message }}</p>@enderror</div>
                    <div><label for="kategori_id">Kategori</label><select id="kategori_id" name="kategori_id" required><option value="">Pilih Kategori</option>@foreach ($kategoris as $kategori)<option value="{{ $kategori->id }}" @selected(old('kategori_id') == $kategori->id)>{{ $kategori->nama }}</option>@endforeach</select>@error('kategori_id')<p class="form-error">{{ $message }}</p>@enderror</div>
                    <div><label for="tahun_terbit">Tahun Terbit</label><input id="tahun_terbit" name="tahun_terbit" type="number" value="{{ old('tahun_terbit') }}" required>@error('tahun_terbit')<p class="form-error">{{ $message }}</p>@enderror</div>
                </div>
                <div class="admin-form-actions"><button type="submit" class="admin-form-submit">Simpan Buku</button><a href="{{ route('petugas.buku.index') }}" class="admin-form-back">Kembali</a></div>
            </form>
        </div>
    @endif

    <!-- TOMBOL KEMBALI -->
    <div style="margin-bottom: 20px;">
        <a href="{{ route('petugas.dashboard') }}"
           class="inline-flex items-center gap-2 font-semibold text-red-800 hover:text-red-950">
            <span aria-hidden="true" class="text-xl">&larr;</span> Kembali
        </a>
    </div>

    <!-- NOTIFIKASI BERHASIL -->
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- NOTIFIKASI GAGAL -->
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- DAFTAR BUKU -->
    <div class="bg-white p-6 shadow-sm rounded-lg">

        <div class="flex flex-wrap justify-between items-center gap-3 mb-5">

            <h3 class="text-lg font-semibold text-gray-800">
                Daftar Buku
            </h3>

            <a href="{{ route('petugas.buku.index', ['tambah' => 1]) }}"
               class="admin-add-button">
                + Tambah Buku
            </a>

        </div>

        <!-- TABEL BUKU -->
        <div class="overflow-x-auto">

            <table class="w-full min-w-max border-collapse border border-gray-300">

                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 p-3">No</th>
                        <th class="border border-gray-300 p-3">Judul</th>
                        <th class="border border-gray-300 p-3">Penulis</th>
                        <th class="border border-gray-300 p-3">Kategori</th>
                        <th class="border border-gray-300 p-3">Tahun Terbit</th>
                        <th class="border border-gray-300 p-3">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($bukus as $buku)

                        <tr class="hover:bg-gray-50">

                            <td class="border border-gray-300 p-3 text-center">
                                {{ $loop->iteration + ($bukus->currentPage() - 1) * $bukus->perPage() }}
                            </td>

                            <td class="border border-gray-300 p-3">
                                {{ $buku->judul }}
                            </td>

                            <td class="border border-gray-300 p-3">
                                {{ $buku->penulis }}
                            </td>

                            <td class="border border-gray-300 p-3">
                                {{ $buku->kategori->nama ?? '-' }}
                            </td>

                            <td class="border border-gray-300 p-3 text-center">
                                {{ $buku->tahun_terbit }}
                            </td>

                            <td class="border border-gray-300 p-3 whitespace-nowrap">

                                <div style="display: flex; align-items: center; justify-content: center; gap: 8px;">

                                    @can('loans.request')
                                        <form action="{{ route('peminjaman.request', $buku) }}" method="POST">
                                            @csrf

                                            <button type="submit"
                                                    style="display: inline-block; padding: 8px 12px; background-color: #2563eb; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                                                Ajukan Pinjam
                                            </button>
                                        </form>
                                    @endcan

                                    <!-- EDIT -->
                                    <a href="{{ route('petugas.buku.index', ['edit' => $buku->id]) }}"
                                       style="display: inline-block; padding: 8px 12px; background-color: #eab308; color: white; border-radius: 6px; text-decoration: none; font-weight: 600;">
                                        Edit
                                    </a>

                                    <!-- HAPUS -->
                                    <form action="{{ route('petugas.buku.destroy', $buku->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus buku ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                style="padding: 8px 12px; background-color: #dc2626; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6"
                                class="border border-gray-300 p-4 text-center text-gray-500">
                                Belum ada data buku.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <!-- PAGINATION -->
        <div class="mt-5">
            {{ $bukus->links() }}
        </div>

    </div>

</x-petugas-layout>
