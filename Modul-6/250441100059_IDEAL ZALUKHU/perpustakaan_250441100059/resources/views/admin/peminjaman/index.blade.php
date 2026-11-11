
<x-admin-layout>

    {{-- JUDUL HALAMAN --}}
    <h1 class="admin-page-title">
        Manajemen Peminjaman
    </h1>

    @if (request()->boolean('tambah') || $errors->any())
        <div class="admin-information" style="margin-top: 0; margin-bottom: 20px;">
            <h2 class="admin-section-title">Formulir Tambah Peminjaman</h2>
            <form action="{{ route('admin.peminjaman.store') }}" method="POST" class="admin-inline-form">
                @csrf
                <div class="admin-form-grid">
                    <div>
                        <label for="anggota_id">Nama Anggota</label>
                        <select id="anggota_id" name="anggota_id" required>
                            <option value="">Pilih Anggota</option>
                            @foreach ($anggotas as $anggota)
                                <option value="{{ $anggota->id }}" @selected(old('anggota_id') == $anggota->id)>{{ $anggota->nama }}</option>
                            @endforeach
                        </select>
                        @error('anggota_id')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="buku_id">Judul Buku</label>
                        <select id="buku_id" name="buku_id" required>
                            <option value="">Pilih Buku</option>
                            @foreach ($bukus as $buku)
                                <option value="{{ $buku->id }}" @selected(old('buku_id') == $buku->id)>{{ $buku->judul }}</option>
                            @endforeach
                        </select>
                        @error('buku_id')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="tanggal_pinjam">Tanggal Pinjam</label>
                        <input id="tanggal_pinjam" name="tanggal_pinjam" type="date" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                        @error('tanggal_pinjam')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="admin-form-actions">
                    <button type="submit" class="admin-form-submit">Simpan Peminjaman</button>
                    <a href="{{ route('admin.peminjaman.index') }}" class="admin-form-back">Kembali</a>
                </div>
            </form>
        </div>
    @endif

    {{-- TOMBOL KEMBALI --}}
    <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.dashboard') }}"
           class="inline-flex items-center gap-2 font-semibold text-red-800 hover:text-red-950">
            <span aria-hidden="true" class="text-xl">&larr;</span> Kembali
        </a>
    </div>

    {{-- KONTEN UTAMA --}}
    <div class="admin-information" style="margin-top: 0;">

        {{-- JUDUL DAN TOMBOL TAMBAH --}}
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap; margin-bottom: 25px;">

            <h3 class="admin-section-title" style="margin-bottom: 0;">
                Daftar Seluruh Peminjaman
            </h3>

            <a href="{{ route('admin.peminjaman.index', ['tambah' => 1]) }}"
               class="admin-add-button">
                + Tambah Peminjaman
            </a>

        </div>

        {{-- NOTIFIKASI BERHASIL --}}
        @if (session('success'))
            <div style="background-color: #dcfce7; color: #166534; padding: 12px; margin-bottom: 16px; border-radius: 6px;">
                {{ session('success') }}
            </div>
        @endif

        {{-- NOTIFIKASI GAGAL --}}
        @if (session('error'))
            <div style="background-color: #fee2e2; color: #991b1b; padding: 12px; margin-bottom: 16px; border-radius: 6px;">
                {{ session('error') }}
            </div>
        @endif

        {{-- TABEL PEMINJAMAN --}}
        <div style="overflow-x: auto;">

            <table style="width: 100%; border-collapse: collapse; background-color: white;">

                <thead>
                    <tr style="background-color: #f1f5f9;">
                        <th style="border: 1px solid #e2e8f0; padding: 13px;">No</th>
                        <th style="border: 1px solid #e2e8f0; padding: 13px;">Nama Anggota</th>
                        <th style="border: 1px solid #e2e8f0; padding: 13px;">Judul Buku</th>
                        <th style="border: 1px solid #e2e8f0; padding: 13px;">Tanggal Pinjam</th>
                        <th style="border: 1px solid #e2e8f0; padding: 13px;">Tanggal Kembali</th>
                        <th style="border: 1px solid #e2e8f0; padding: 13px;">Status</th>
                        <th style="border: 1px solid #e2e8f0; padding: 13px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($peminjamans as $peminjaman)

                        <tr>

                            {{-- NOMOR --}}
                            <td style="border: 1px solid #e2e8f0; padding: 13px; text-align: center;">
                                {{ $loop->iteration }}
                            </td>

                            {{-- NAMA ANGGOTA --}}
                            <td style="border: 1px solid #e2e8f0; padding: 13px;">
                                {{ $peminjaman->anggota->nama ?? '-' }}
                            </td>

                            {{-- JUDUL BUKU --}}
                            <td style="border: 1px solid #e2e8f0; padding: 13px;">
                                {{ $peminjaman->buku->judul ?? '-' }}
                            </td>

                            {{-- TANGGAL PINJAM --}}
                            <td style="border: 1px solid #e2e8f0; padding: 13px; text-align: center; white-space: nowrap;">
                                {{ $peminjaman->tanggal_pinjam }}
                            </td>

                            {{-- TANGGAL KEMBALI --}}
                            <td style="border: 1px solid #e2e8f0; padding: 13px; text-align: center; white-space: nowrap;">
                                {{ $peminjaman->tanggal_kembali ?? '-' }}
                            </td>

                            {{-- STATUS --}}
                            <td style="border: 1px solid #e2e8f0; padding: 13px; text-align: center; white-space: nowrap;">

                                @if ($peminjaman->status === 'menunggu')
                                    <span style="color: #b45309; font-weight: bold;">Menunggu Persetujuan</span>
                                @elseif ($peminjaman->status === 'ditolak')
                                    <span style="color: #b91c1c; font-weight: bold;">Ditolak</span>
                                @elseif ($peminjaman->status === 'dikembalikan' || $peminjaman->tanggal_kembali)

                                    <span style="color: #15803d; font-weight: bold;">
                                        Sudah Dikembalikan
                                    </span>

                                @elseif (in_array($peminjaman->status, ['disetujui', 'dipinjam'], true))
                                    <span style="display: inline-flex; align-items: center; gap: 6px; border: 1px solid #bfdbfe; border-radius: 9999px; background-color: #eff6ff; color: #1d4ed8; padding: 5px 10px; font-weight: 600;">
                                        <span aria-hidden="true">●</span> Sedang Dipinjam
                                    </span>
                                @else
                                    <span style="display: inline-flex; align-items: center; border: 1px solid #cbd5e1; border-radius: 9999px; color: #475569; padding: 5px 10px; font-weight: 600;">{{ ucfirst($peminjaman->status) }}</span>

                                @endif

                            </td>

                            {{-- AKSI --}}
                            <td style="border: 1px solid #e2e8f0; padding: 13px; text-align: center;">

                                @if ($peminjaman->status === 'menunggu')
                                    <div style="display: flex; justify-content: center; gap: 8px; flex-wrap: wrap;">
                                        <form action="{{ route('admin.peminjaman.proses', [$peminjaman, 'disetujui']) }}" method="POST">
                                            @csrf
                                            <button type="submit" style="border: 1px solid #166534; background-color: #f0fdf4; color: #166534; padding: 7px 11px; border-radius: 6px; cursor: pointer; font-weight: 600;">✓ Setujui</button>
                                        </form>
                                        <form action="{{ route('admin.peminjaman.proses', [$peminjaman, 'ditolak']) }}" method="POST">
                                            @csrf
                                            <button type="submit" style="border: 1px solid #b91c1c; background-color: #fef2f2; color: #b91c1c; padding: 7px 11px; border-radius: 6px; cursor: pointer; font-weight: 600;">× Tolak</button>
                                        </form>
                                    </div>
                                @elseif (in_array($peminjaman->status, ['disetujui', 'dipinjam'], true) && !$peminjaman->tanggal_kembali)

                                    <form
                                        action="{{ route('admin.peminjaman.kembalikan', $peminjaman->id) }}"
                                        method="POST"
                                        style="margin: 0;">

                                        @csrf

                                        <button
                                            type="submit"
                                            onclick="return confirm('Apakah buku ini sudah dikembalikan?')"
                                            style="border: 1px solid #1d4ed8; background-color: #eff6ff; color: #1d4ed8; padding: 7px 11px; border-radius: 6px; cursor: pointer; white-space: nowrap; font-weight: 600;">
                                            ↩ Kembalikan
                                        </button>

                                    </form>

                                @else

                                    <span style="display: inline-flex; align-items: center; border: 1px solid #bbf7d0; border-radius: 9999px; background-color: #f0fdf4; color: #15803d; padding: 5px 10px; font-weight: 600;">
                                        ✓ Selesai
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" style="border: 1px solid #e2e8f0; padding: 20px; text-align: center;">
                                Belum ada data peminjaman.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</x-admin-layout>
