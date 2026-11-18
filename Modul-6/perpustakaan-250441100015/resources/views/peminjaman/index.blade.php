@extends('layouts.app')

@section('title', 'Manajemen Peminjaman')

@section('content')
    <div class="book-page">
        <div class="page-header" data-aos="fade-down" data-aos-duration="800">
            <span class="page-label">PENGELOLAAN PERPUSTAKAAN</span>
            <h1>📋 Manajemen Peminjaman</h1>
            <p>Kelola pengajuan peminjaman buku dari siswa.</p>
        </div>

        <div class="book-form-wrapper" data-aos="fade-up" data-aos-duration="700" data-aos-delay="100">
            <h2>Daftar Pengajuan Peminjaman</h2>

            @if (session('success'))
                <p class="alert-success" role="status">{{ session('success') }}</p>
            @endif

            @if (session('error'))
                <p class="alert-error" role="alert">{{ session('error') }}</p>
            @endif

            @if ($peminjamans->isEmpty())
                <div class="flex flex-col items-center justify-center py-14 text-gray-400">
                    <span class="text-5xl mb-3">📭</span>
                    <p class="text-base font-medium">Belum ada pengajuan peminjaman.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Anggota</th>
                                <th>Judul Buku</th>
                                <th>Tgl. Pinjam</th>
                                <th>Tgl. Kembali</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($peminjamans as $peminjaman)
                                <tr>
                                    <td class="text-gray-400 font-mono text-xs">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="font-medium text-gray-800">{{ $peminjaman->anggota->nama }}</span>
                                    </td>
                                    <td>
                                        <span class="font-medium text-indigo-700">{{ $peminjaman->buku->judul }}</span>
                                    </td>
                                    <td class="text-gray-600 text-xs">
                                        {{ $peminjaman->tanggal_pinjam->format('d-m-Y') }}
                                    </td>
                                    <td class="text-gray-600 text-xs">
                                        {{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d-m-Y') : '-' }}
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($peminjaman->status) {
                                                'menunggu'    => 'badge-menunggu',
                                                'disetujui'   => 'badge-disetujui',
                                                'ditolak'     => 'badge-ditolak',
                                                'dikembalikan'=> 'badge-dikembalikan',
                                                default       => 'bg-gray-100 text-gray-700 border-gray-200 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold',
                                            };
                                            $statusIcon = match($peminjaman->status) {
                                                'menunggu'    => '⏳',
                                                'disetujui'   => '✅',
                                                'ditolak'     => '❌',
                                                'dikembalikan'=> '📦',
                                                default       => '•',
                                            };
                                        @endphp
                                        <span class="{{ $statusClass }}">
                                            {{ $statusIcon }} {{ ucfirst($peminjaman->status) }}
                                        </span>
                                    </td>

                                    <td>
                                        @if ($peminjaman->status === 'menunggu')
                                            <div class="action-buttons">
                                                <!-- Setujui -->
                                                <form action="{{ route('peminjaman.approve', $peminjaman->id) }}"
                                                      method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="px-3 py-1.5 text-xs font-semibold text-white rounded-lg
                                                                   transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                                            style="background: linear-gradient(135deg, #10B981, #059669);">
                                                        ✅ Setujui
                                                    </button>
                                                </form>

                                                <!-- Tolak -->
                                                <form action="{{ route('peminjaman.reject', $peminjaman->id) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Yakin ingin menolak pengajuan ini?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="px-3 py-1.5 text-xs font-semibold text-white rounded-lg
                                                                   transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                                            style="background: linear-gradient(135deg, #EF4444, #DC2626);">
                                                        ❌ Tolak
                                                    </button>
                                                </form>
                                            </div>

                                        @elseif ($peminjaman->status === 'disetujui')
                                            <form action="{{ route('peminjaman.return', $peminjaman->id) }}" method="POST"
                                                  onsubmit="return confirm('Catat pengembalian buku ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="px-3 py-1.5 text-xs font-semibold text-white rounded-lg
                                                               transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                                        style="background: linear-gradient(135deg, #3B82F6, #2563EB);">
                                                    📦 Kembalikan
                                                </button>
                                            </form>

                                        @else
                                            <span class="text-xs text-gray-400 italic">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
