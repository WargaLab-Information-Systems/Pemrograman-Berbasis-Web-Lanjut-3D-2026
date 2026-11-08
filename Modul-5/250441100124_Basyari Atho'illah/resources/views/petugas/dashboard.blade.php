<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-sky-500/10 text-sky-600 flex items-center justify-center text-base">
                        <i class="fa-solid fa-shield-halved"></i>
                    </span>
                    Dashboard Operasional Petugas
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Selamat bertugas, <strong>{{ Auth::user()->name }}</strong>. Layani sirkulasi peminjaman, pengembalian, dan katalog perpustakaan.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('buku.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-2xs transition">
                    <i class="fa-solid fa-book"></i> Kelola Data Buku
                </a>
                <a href="{{ route('peminjaman.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-xl text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 shadow-2xs transition">
                    <i class="fa-solid fa-clipboard-list"></i> Sirkulasi Lengkap
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- 4 KARTU STATISTIK OPERASIONAL --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Total Koleksi Buku -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Total Koleksi Buku</p>
                            <h3 class="text-3xl font-extrabold text-slate-800 mt-1.5">{{ number_format($totalBuku) }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-inner">
                            <i class="fa-solid fa-book"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-xs text-slate-500">
                        Katalog siap disirkulasikan
                    </div>
                </div>

                <!-- Pengajuan Menunggu -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Menunggu Verifikasi</p>
                            <h3 class="text-3xl font-extrabold text-amber-500 mt-1.5">{{ number_format($totalMenunggu) }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shadow-inner">
                            <i class="fa-solid fa-hourglass-half"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-xs text-slate-500">
                        Perlu diproses oleh petugas
                    </div>
                </div>

                <!-- Buku Sedang Dipinjam -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Sedang Dipinjam</p>
                            <h3 class="text-3xl font-extrabold text-sky-600 mt-1.5">{{ number_format($totalDipinjam) }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl shadow-inner">
                            <i class="fa-solid fa-book-open-reader"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-xs text-slate-500">
                        Buku beredar di tangan siswa
                    </div>
                </div>

                <!-- Selesai Dikembalikan -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Telah Dikembalikan</p>
                            <h3 class="text-3xl font-extrabold text-emerald-600 mt-1.5">{{ number_format($totalDikembalikan) }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-inner">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-xs text-slate-500">
                        Transaksi selesai & aman
                    </div>
                </div>
            </div>

            {{-- ANTREAN PENGAJUAN PINJAMAN (MEMERLUKAN TINDAKAN PETUGAS) --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-amber-50/50">
                    <div>
                        <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-md bg-amber-100 text-amber-700 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-bell"></i>
                            </span>
                            Antrean Pengajuan Peminjaman Baru
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Siswa mengajukan buku dan menunggu persetujuan dari petugas perpustakaan.</p>
                    </div>
                    <span class="px-2.5 py-1 text-xs font-bold bg-amber-100 text-amber-800 rounded-full">
                        {{ $totalMenunggu }} Menunggu
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50/75 text-xs uppercase tracking-wider text-slate-400 font-semibold border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3">Nama Siswa</th>
                                <th class="px-6 py-3">Judul Buku Diajukan</th>
                                <th class="px-6 py-3">Tgl Pengajuan</th>
                                <th class="px-6 py-3 text-right">Aksi Persetujuan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($antreanPeminjaman as $antrean)
                                <tr class="hover:bg-amber-50/30 transition">
                                    <td class="px-6 py-3.5">
                                        <div class="font-semibold text-slate-800">{{ $antrean->user->name ?? $antrean->anggota->nama ?? '-' }}</div>
                                        <div class="text-xs text-slate-400">{{ $antrean->user->email ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-3.5">
                                        <div class="font-medium text-slate-800">{{ $antrean->buku->judul ?? '-' }}</div>
                                        <div class="text-xs text-slate-400">Kategori: {{ $antrean->buku->kategoriRelasi->nama ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-3.5 text-xs text-slate-500">
                                        {{ \Carbon\Carbon::parse($antrean->tanggal_pinjam)->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="px-6 py-3.5 text-right">
                                        <div class="inline-flex items-center gap-2">
                                            <!-- Tombol Setujui -->
                                            <form action="{{ route('peminjaman.setujui', $antrean->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 shadow-2xs transition">
                                                    <i class="fa-solid fa-check"></i> Setujui
                                                </button>
                                            </form>

                                            <!-- Tombol Tolak -->
                                            <form action="{{ route('peminjaman.tolak', $antrean->id) }}" method="POST" onsubmit="return confirm('Tolak pengajuan peminjaman ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 transition">
                                                    <i class="fa-solid fa-xmark"></i> Tolak
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-400">
                                        <i class="fa-solid fa-circle-check text-emerald-500 text-lg mb-1 block"></i>
                                        Tidak ada antrean pengajuan peminjaman baru saat ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- PEMINJAMAN AKTIF & CATAT PENGEMBALIAN --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-book-reader text-sky-500"></i> Peminjaman Buku Aktif
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Daftar buku yang sedang dibawa oleh siswa. Catat pengembalian saat buku dikembalikan.</p>
                    </div>
                    <a href="{{ route('peminjaman.index', ['status' => 'dipinjam']) }}" class="text-xs font-semibold text-sky-600 hover:text-sky-800">
                        Lihat Seluruhnya &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50/75 text-xs uppercase tracking-wider text-slate-400 font-semibold border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3">Nama Siswa</th>
                                <th class="px-6 py-3">Judul Buku</th>
                                <th class="px-6 py-3">Tgl Pinjam</th>
                                <th class="px-6 py-3 text-right">Aksi Sirkulasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($peminjamanAktif as $aktif)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-3.5 font-semibold text-slate-800">
                                        {{ $aktif->user->name ?? $aktif->anggota->nama ?? '-' }}
                                    </td>
                                    <td class="px-6 py-3.5">
                                        <div class="font-medium text-slate-800">{{ $aktif->buku->judul ?? '-' }}</div>
                                        <div class="text-xs text-slate-400">ID Buku: #{{ $aktif->buku_id }}</div>
                                    </td>
                                    <td class="px-6 py-3.5 text-xs text-slate-500">
                                        {{ \Carbon\Carbon::parse($aktif->tanggal_pinjam)->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="px-6 py-3.5 text-right">
                                        <form action="{{ route('peminjaman.kembalikan', $aktif->id) }}" method="POST" onsubmit="return confirm('Konfirmasi bahwa buku telah diterima dan dikembalikan ke rak?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-2xs transition">
                                                <i class="fa-solid fa-inbox"></i> Catat Pengembalian
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-400">
                                        Belum ada buku yang sedang berstatus dipinjam.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
