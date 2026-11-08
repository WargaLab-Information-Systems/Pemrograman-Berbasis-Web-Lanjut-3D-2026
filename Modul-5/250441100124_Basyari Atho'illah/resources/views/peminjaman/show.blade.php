<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    Detail Peminjaman Buku #{{ $peminjaman->id }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Informasi lengkap mengenai status transaksi peminjaman perpustakaan.
                </p>
            </div>
            <div>
                @if (auth()->user()->hasRole('siswa'))
                    <a href="{{ route('siswa.peminjaman.riwayat') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-xl text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 transition">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat Saya
                    </a>
                @else
                    <a href="{{ route('peminjaman.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-xl text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 transition">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Peminjaman
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Header Banner -->
                <div class="p-6 bg-slate-900 text-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <div>
                            <span class="text-xs font-mono text-indigo-300 uppercase tracking-wider">Tiket Transaksi #{{ $peminjaman->id }}</span>
                            <h3 class="text-lg font-bold text-white mt-0.5">{{ $peminjaman->buku->judul ?? '-' }}</h3>
                        </div>
                    </div>
                    <div>
                        @if ($peminjaman->status === 'menunggu')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-amber-400 text-amber-950">
                                <i class="fa-solid fa-hourglass-half"></i> Menunggu Verifikasi
                            </span>
                        @elseif ($peminjaman->status === 'dipinjam')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-sky-400 text-sky-950">
                                <i class="fa-solid fa-book-reader"></i> Sedang Dipinjam
                            </span>
                        @elseif ($peminjaman->status === 'dikembalikan')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-400 text-emerald-950">
                                <i class="fa-solid fa-circle-check"></i> Selesai Dikembalikan
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-rose-400 text-rose-950">
                                <i class="fa-solid fa-circle-xmark"></i> Ditolak
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Detail Body -->
                <div class="p-6 sm:p-8 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Info Peminjam -->
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 flex items-center gap-1.5">
                                <i class="fa-solid fa-user text-slate-400"></i> Identitas Peminjam
                            </h4>
                            <div class="space-y-1.5 text-sm">
                                <div class="font-bold text-slate-800">{{ $peminjaman->user->name ?? $peminjaman->anggota->nama ?? '-' }}</div>
                                <div class="text-xs text-slate-500 font-mono">{{ $peminjaman->user->email ?? $peminjaman->anggota->email ?? '-' }}</div>
                                <div class="text-xs text-slate-500">ID Pengguna: #{{ $peminjaman->user_id ?? $peminjaman->anggota_id }}</div>
                            </div>
                        </div>

                        <!-- Info Buku -->
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 flex items-center gap-1.5">
                                <i class="fa-solid fa-book text-slate-400"></i> Informasi Buku
                            </h4>
                            <div class="space-y-1.5 text-sm">
                                <div class="font-bold text-slate-800">{{ $peminjaman->buku->judul ?? '-' }}</div>
                                <div class="text-xs text-slate-500">Penulis: {{ $peminjaman->buku->penulis ?? '-' }}</div>
                                <div class="text-xs text-slate-500">Kategori: {{ $peminjaman->buku->kategoriRelasi->nama ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Tanggal -->
                    <div class="border-t border-b border-slate-100 py-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-xs text-slate-400 block mb-0.5">Tanggal Mulai Pinjam / Pengajuan:</span>
                            <span class="font-semibold text-slate-800">
                                {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('l, d F Y') }}
                            </span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block mb-0.5">Tanggal Selesai Dikembalikan:</span>
                            @if ($peminjaman->tanggal_kembali)
                                <span class="font-semibold text-emerald-600">
                                    {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->translatedFormat('l, d F Y') }}
                                </span>
                            @else
                                <span class="text-slate-400 italic">Belum dikembalikan</span>
                            @endif
                        </div>
                    </div>

                    <!-- Catatan Petugas / Sistem -->
                    @if ($peminjaman->catatan)
                        <div class="rounded-xl bg-indigo-50/60 border border-indigo-100 p-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-700 mb-1">
                                Catatan Sistem & Petugas
                            </h4>
                            <p class="text-xs text-indigo-900 leading-relaxed">{{ $peminjaman->catatan }}</p>
                        </div>
                    @endif

                    <!-- Tombol Aksi Petugas / Admin -->
                    @hasanyrole('admin|petugas')
                        <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center justify-end gap-3">
                            @if ($peminjaman->status === 'menunggu')
                                <form action="{{ route('peminjaman.setujui', $peminjaman->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-4 py-2 text-xs font-semibold rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 transition">
                                        <i class="fa-solid fa-check me-1"></i> Setujui Peminjaman
                                    </button>
                                </form>
                                <form action="{{ route('peminjaman.tolak', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Tolak permohonan peminjaman ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-4 py-2 text-xs font-semibold rounded-xl text-rose-700 bg-rose-50 hover:bg-rose-100 transition">
                                        <i class="fa-solid fa-xmark me-1"></i> Tolak Pengajuan
                                    </button>
                                </form>
                            @elseif ($peminjaman->status === 'dipinjam')
                                <form action="{{ route('peminjaman.kembalikan', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Catat pengembalian buku ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-4 py-2 text-xs font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 transition">
                                        <i class="fa-solid fa-inbox me-1"></i> Catat Pengembalian Buku
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endhasanyrole
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
