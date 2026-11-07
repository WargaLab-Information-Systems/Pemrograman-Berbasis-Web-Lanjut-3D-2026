
<x-admin-layout>

    {{-- JUDUL HALAMAN --}}
    <div class="admin-welcome">
        <h1 class="admin-page-title">
            Manajemen Buku
        </h1>

        <p>
            Kelola seluruh koleksi buku perpustakaan.
        </p>
    </div>

    @if ($bukuEdit)
        <div class="admin-information" style="margin-top: 0; margin-bottom: 20px;">
            <h2 class="admin-section-title">Formulir Edit Buku</h2>
            <form action="{{ route('admin.buku.update', $bukuEdit) }}" method="POST" class="admin-inline-form">
                @csrf
                @method('PUT')
                <div class="admin-form-grid">
                    <div><label for="judul">Judul Buku</label><input id="judul" name="judul" value="{{ old('judul', $bukuEdit->judul) }}" required>@error('judul')<p class="form-error">{{ $message }}</p>@enderror</div>
                    <div><label for="penulis">Penulis</label><input id="penulis" name="penulis" value="{{ old('penulis', $bukuEdit->penulis) }}" required>@error('penulis')<p class="form-error">{{ $message }}</p>@enderror</div>
                    <div><label for="kategori_id">Kategori</label><select id="kategori_id" name="kategori_id" required><option value="">Pilih Kategori</option>@foreach ($kategoris as $kategori)<option value="{{ $kategori->id }}" @selected(old('kategori_id', $bukuEdit->kategori_id) == $kategori->id)>{{ $kategori->nama }}</option>@endforeach</select>@error('kategori_id')<p class="form-error">{{ $message }}</p>@enderror</div>
                    <div><label for="tahun_terbit">Tahun Terbit</label><input id="tahun_terbit" name="tahun_terbit" type="number" value="{{ old('tahun_terbit', $bukuEdit->tahun_terbit) }}" required>@error('tahun_terbit')<p class="form-error">{{ $message }}</p>@enderror</div>
                </div>
                <div class="admin-form-actions"><button type="submit" class="admin-form-submit">Simpan Perubahan</button><a href="{{ route('admin.buku.index') }}" class="admin-form-back">Kembali</a></div>
            </form>
        </div>
    @elseif (request()->boolean('tambah') || $errors->any())
        <div class="admin-information" style="margin-top: 0; margin-bottom: 20px;">
            <h2 class="admin-section-title">Formulir Tambah Buku</h2>
            <form action="{{ route('admin.buku.store') }}" method="POST" class="admin-inline-form">
                @csrf
                <div class="admin-form-grid">
                    <div><label for="judul">Judul Buku</label><input id="judul" name="judul" value="{{ old('judul') }}" required>@error('judul')<p class="form-error">{{ $message }}</p>@enderror</div>
                    <div><label for="penulis">Penulis</label><input id="penulis" name="penulis" value="{{ old('penulis') }}" required>@error('penulis')<p class="form-error">{{ $message }}</p>@enderror</div>
                    <div><label for="kategori_id">Kategori</label><select id="kategori_id" name="kategori_id" required><option value="">Pilih Kategori</option>@foreach ($kategoris as $kategori)<option value="{{ $kategori->id }}" @selected(old('kategori_id') == $kategori->id)>{{ $kategori->nama }}</option>@endforeach</select>@error('kategori_id')<p class="form-error">{{ $message }}</p>@enderror</div>
                    <div><label for="tahun_terbit">Tahun Terbit</label><input id="tahun_terbit" name="tahun_terbit" type="number" value="{{ old('tahun_terbit') }}" required>@error('tahun_terbit')<p class="form-error">{{ $message }}</p>@enderror</div>
                </div>
                <div class="admin-form-actions"><button type="submit" class="admin-form-submit">Simpan Buku</button><a href="{{ route('admin.buku.index') }}" class="admin-form-back">Kembali</a></div>
            </form>
        </div>
    @endif

    {{-- SATU TOMBOL KEMBALI --}}
    <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.dashboard') }}"
           class="inline-flex items-center gap-2 font-semibold text-red-800 hover:text-red-950">
            <span aria-hidden="true" class="text-xl">&larr;</span> Kembali
        </a>
    </div>

    {{-- NOTIFIKASI BERHASIL --}}
    @if (session('success'))
        <div style="margin-bottom: 16px; padding: 14px; background: #dcfce7; color: #166534; border-radius: 8px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- NOTIFIKASI GAGAL --}}
    @if (session('error'))
        <div style="margin-bottom: 16px; padding: 14px; background: #fee2e2; color: #991b1b; border-radius: 8px;">
            {{ session('error') }}
        </div>
    @endif

    {{-- DAFTAR BUKU --}}
    <div class="admin-information" style="margin-top: 0;">

        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">

            <h2 class="admin-section-title" style="margin-bottom: 0;">
                Daftar Buku
            </h2>

            {{-- TOMBOL TAMBAH BUKU --}}
            <a href="{{ route('admin.buku.index', ['tambah' => 1]) }}"
               class="admin-add-button">
                + Tambah Buku
            </a>

        </div>

        {{-- TABEL BUKU --}}
        <div style="overflow-x: auto;">

            <table style="width: 100%; border-collapse: collapse; min-width: 700px;">

                <thead>
                    <tr style="background: #f1f5f9;">
                        <th style="padding: 13px; border: 1px solid #e2e8f0;">No</th>
                        <th style="padding: 13px; border: 1px solid #e2e8f0;">Judul</th>
                        <th style="padding: 13px; border: 1px solid #e2e8f0;">Penulis</th>
                        <th style="padding: 13px; border: 1px solid #e2e8f0;">Kategori</th>
                        <th style="padding: 13px; border: 1px solid #e2e8f0;">Tahun Terbit</th>
                        <th style="padding: 13px; border: 1px solid #e2e8f0;">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($bukus as $buku)

                        <tr style="text-align: center;">

                            {{-- NOMOR --}}
                            <td style="padding: 12px; border: 1px solid #e2e8f0;">
                                {{ $loop->iteration + ($bukus->currentPage() - 1) * $bukus->perPage() }}
                            </td>

                            {{-- JUDUL --}}
                            <td style="padding: 12px; border: 1px solid #e2e8f0; text-align: left;">
                                {{ $buku->judul }}
                            </td>

                            {{-- PENULIS --}}
                            <td style="padding: 12px; border: 1px solid #e2e8f0;">
                                {{ $buku->penulis }}
                            </td>

                            {{-- KATEGORI --}}
                            <td style="padding: 12px; border: 1px solid #e2e8f0;">
                                {{ $buku->kategori->nama ?? '-' }}
                            </td>

                            {{-- TAHUN TERBIT --}}
                            <td style="padding: 12px; border: 1px solid #e2e8f0;">
                                {{ $buku->tahun_terbit }}
                            </td>

                            {{-- AKSI --}}
                            <td style="padding: 12px; border: 1px solid #e2e8f0;">

                                <div style="display: flex; justify-content: center; gap: 7px;">

                                    @can('loans.request')
                                        <form action="{{ route('peminjaman.request', $buku) }}" method="POST">
                                            @csrf

                                            <button type="submit"
                                                    style="padding: 8px 12px; background: #2563eb; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                                                Ajukan Pinjam
                                            </button>
                                        </form>
                                    @endcan

                                    {{-- EDIT --}}
                                    <a href="{{ route('admin.buku.index', ['edit' => $buku->id]) }}"
                                       style="padding: 8px 12px; background: #eab308; color: white; border-radius: 6px; text-decoration: none; font-weight: 600;">
                                        Edit
                                    </a>

                                    {{-- HAPUS --}}
                                    <form action="{{ route('admin.buku.destroy', $buku->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus buku ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                style="padding: 8px 12px; background: #dc2626; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" style="padding: 20px; text-align: center; border: 1px solid #e2e8f0; color: #64748b;">
                                Belum ada data buku.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        <div style="margin-top: 20px;">
            {{ $bukus->links() }}
        </div>

    </div>

</x-admin-layout>
