<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center text-base">
                        <i class="fa-solid fa-gauge-high"></i>
                    </span>
                    Dashboard Administrasi Utama
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong> (Admin). Kelola sistem perpustakaan digital secara menyeluruh.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-2xs transition">
                    <i class="fa-solid fa-users-gear"></i> Kelola User & Role
                </a>
                <a href="{{ route('peminjaman.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-xl text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 shadow-2xs transition">
                    <i class="fa-solid fa-clipboard-list"></i> Peminjaman
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- 4 KARTU STATISTIK UTAMA --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Total Koleksi Buku -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Total Koleksi Buku</p>
                            <h3 class="text-3xl font-extrabold text-slate-800 mt-1.5">{{ number_format($totalBuku) }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-inner">
                            <i class="fa-solid fa-book"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>Katalog aktif</span>
                        <a href="{{ route('buku.index') }}" class="text-indigo-600 font-semibold hover:underline">Kelola Buku &rarr;</a>
                    </div>
                </div>

                <!-- Total Akun Pengguna -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Total Pengguna</p>
                            <h3 class="text-3xl font-extrabold text-slate-800 mt-1.5">{{ number_format($totalPengguna) }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl shadow-inner">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>Admin, Petugas, Siswa</span>
                        <a href="{{ route('admin.users.index') }}" class="text-sky-600 font-semibold hover:underline">Kelola &rarr;</a>
                    </div>
                </div>

                <!-- Peminjaman Aktif -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Peminjaman Aktif</p>
                            <h3 class="text-3xl font-extrabold text-emerald-600 mt-1.5">{{ number_format($totalPeminjamanAktif) }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-inner">
                            <i class="fa-solid fa-book-reader"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>Buku sedang dipinjam</span>
                        <a href="{{ route('peminjaman.index', ['status' => 'dipinjam']) }}" class="text-emerald-600 font-semibold hover:underline">Lihat &rarr;</a>
                    </div>
                </div>

                <!-- Menunggu Persetujuan -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Menunggu Persetujuan</p>
                            <h3 class="text-3xl font-extrabold text-amber-500 mt-1.5">{{ number_format($totalMenunggu) }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shadow-inner">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>Pengajuan baru siswa</span>
                        <a href="{{ route('peminjaman.index', ['status' => 'menunggu']) }}" class="text-amber-600 font-semibold hover:underline">Proses &rarr;</a>
                    </div>
                </div>
            </div>

            {{-- TABEL PEMINJAMAN TERBARU --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-list-check text-indigo-500"></i> Aktivitas Peminjaman Terkini
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Pantau alur transaksi peminjaman dan pengembalian perpustakaan.</p>
                    </div>
                    <a href="{{ route('peminjaman.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                        Lihat Seluruhnya &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50/75 text-xs uppercase tracking-wider text-slate-400 font-semibold border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3">Peminjam</th>
                                <th class="px-6 py-3">Judul Buku</th>
                                <th class="px-6 py-3">Tgl Pinjam</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($peminjamanTerbaru as $p)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-3.5">
                                        <div class="font-semibold text-slate-800">{{ $p->user->name ?? $p->anggota->nama ?? 'Pengguna' }}</div>
                                        <div class="text-xs text-slate-400">{{ $p->user->email ?? $p->anggota->email ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-3.5">
                                        <div class="font-medium text-slate-800">{{ $p->buku->judul ?? '-' }}</div>
                                        <div class="text-xs text-slate-400">Penulis: {{ $p->buku->penulis ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-3.5 text-xs text-slate-500">
                                        {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="px-6 py-3.5">
                                        @if ($p->status === 'menunggu')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                                <i class="fa-solid fa-hourglass-half text-[10px]"></i> Menunggu
                                            </span>
                                        @elseif ($p->status === 'dipinjam')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                                <i class="fa-solid fa-book-reader text-[10px]"></i> Dipinjam
                                            </span>
                                        @elseif ($p->status === 'dikembalikan')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                                <i class="fa-solid fa-check text-[10px]"></i> Selesai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">
                                                <i class="fa-solid fa-xmark text-[10px]"></i> Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5 text-right">
                                        <a href="{{ route('peminjaman.show', $p->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                        Belum ada data aktivitas peminjaman.
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
