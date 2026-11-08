<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-base">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </span>
                    Dashboard Perpustakaan Siswa
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Selamat datang, <strong>{{ Auth::user()->name }}</strong>! Akses layanan perpustakaan digital secara mandiri.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('buku.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-2xs transition">
                    <i class="fa-solid fa-book-open"></i> Buka Katalog Buku
                </a>
                <a href="{{ route('siswa.peminjaman.riwayat') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-xl text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 shadow-2xs transition">
                    <i class="fa-solid fa-clock-rotate-left"></i> Pinjaman Saya
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $userId = Auth::id();
        $aktifCount = \App\Models\Peminjaman::where('user_id', $userId)->where('status', 'dipinjam')->count();
        $menungguCount = \App\Models\Peminjaman::where('user_id', $userId)->where('status', 'menunggu')->count();
        $selesaiCount = \App\Models\Peminjaman::where('user_id', $userId)->where('status', 'dikembalikan')->count();
        $pinjamanTerbaru = \App\Models\Peminjaman::with('buku')->where('user_id', $userId)->latest()->take(5)->get();
    @endphp

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- BANNER SAMBUTAN --}}
            <div class="rounded-3xl bg-gradient-to-r from-indigo-700 via-indigo-600 to-violet-600 p-6 sm:p-8 text-white shadow-md relative overflow-hidden">
                <div class="relative z-10 max-w-xl">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white backdrop-blur-xs mb-3">
                        <i class="fa-solid fa-circle-check"></i> Akun Siswa Aktif &bull; Role-Based Access Control
                    </span>
                    <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Jelajahi Dunia Lewat Membaca Buku</h3>
                    <p class="mt-2 text-sm text-indigo-100 leading-relaxed">
                        Anda dapat melihat seluruh katalog buku perpustakaan, mengajukan peminjaman secara online, dan memantau status peminjaman Anda secara transparan.
                    </p>
                    <div class="mt-5 flex items-center gap-3">
                        <a href="{{ route('buku.index') }}" class="px-4 py-2.5 rounded-xl bg-white text-indigo-700 text-xs font-bold hover:bg-indigo-50 shadow-sm transition">
                            Cari & Pinjam Buku Sekarang &rarr;
                        </a>
                    </div>
                </div>
                <div class="absolute -right-8 -bottom-10 opacity-20 text-white pointer-events-none hidden md:block">
                    <i class="fa-solid fa-book-bookmark text-[220px]"></i>
                </div>
            </div>

            {{-- 3 KARTU STATUS SISWA --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Buku Sedang Dipinjam</p>
                            <h4 class="text-3xl font-extrabold text-sky-600 mt-1">{{ $aktifCount }}</h4>
                        </div>
                        <div class="w-11 h-11 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl">
                            <i class="fa-solid fa-book-reader"></i>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 mt-3">Buku aktif di tangan Anda</p>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Menunggu Verifikasi</p>
                            <h4 class="text-3xl font-extrabold text-amber-500 mt-1">{{ $menungguCount }}</h4>
                        </div>
                        <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                            <i class="fa-solid fa-hourglass-half"></i>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 mt-3">Sedang ditinjau petugas</p>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Selesai Dikembalikan</p>
                            <h4 class="text-3xl font-extrabold text-emerald-600 mt-1">{{ $selesaiCount }}</h4>
                        </div>
                        <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 mt-3">Riwayat peminjaman yang tuntas</p>
                </div>
            </div>

            {{-- TABEL STATUS PINJAMAN PRIBADI TERBARU --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-slate-800 text-base">Status Peminjaman Buku Terbaru</h4>
                        <p class="text-xs text-slate-500">Pantau buku yang baru saja Anda ajukan.</p>
                    </div>
                    <a href="{{ route('siswa.peminjaman.riwayat') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                        Lihat Seluruh Riwayat &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50/75 text-xs uppercase tracking-wider text-slate-400 font-semibold border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3">Buku</th>
                                <th class="px-6 py-3">Tanggal Pinjam</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($pinjamanTerbaru as $pt)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-6 py-3.5">
                                        <div class="font-semibold text-slate-800">{{ $pt->buku->judul ?? '-' }}</div>
                                        <div class="text-xs text-slate-400">{{ $pt->buku->penulis ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-3.5 text-xs">
                                        {{ \Carbon\Carbon::parse($pt->tanggal_pinjam)->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="px-6 py-3.5">
                                        @if ($pt->status === 'menunggu')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">
                                                <i class="fa-solid fa-hourglass-half text-[10px]"></i> Menunggu
                                            </span>
                                        @elseif ($pt->status === 'dipinjam')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-sky-100 text-sky-800">
                                                <i class="fa-solid fa-book-reader text-[10px]"></i> Dipinjam
                                            </span>
                                        @elseif ($pt->status === 'dikembalikan')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                                <i class="fa-solid fa-circle-check text-[10px]"></i> Selesai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-rose-100 text-rose-800">
                                                <i class="fa-solid fa-xmark text-[10px]"></i> Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5 text-right">
                                        <a href="{{ route('peminjaman.show', $pt->id) }}" class="px-2.5 py-1.5 rounded-lg text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-400">
                                        Belum ada data peminjaman buku. Buka katalog untuk meminjam buku.
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
