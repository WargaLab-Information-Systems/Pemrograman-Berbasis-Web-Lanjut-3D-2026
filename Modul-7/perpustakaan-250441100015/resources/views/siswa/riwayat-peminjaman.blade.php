@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
    <div class="book-page">
        <div class="page-header" data-aos="fade-down" data-aos-duration="800">
            <span class="page-label">AREA SISWA</span>
            <h1>🕐 Riwayat Peminjaman</h1>
            <p>Lihat daftar peminjaman buku milik Anda.</p>
        </div>

        <div class="book-form-wrapper" data-aos="fade-up" data-aos-duration="700" data-aos-delay="100">
            <h2>Daftar Riwayat Buku</h2>

            @if ($peminjamans->isEmpty())
                <div class="flex flex-col items-center justify-center py-14 text-gray-400">
                    <span class="text-5xl mb-3">📭</span>
                    <p class="text-base font-medium">Anda belum memiliki riwayat peminjaman.</p>
                    <a href="{{ route('buku.index') }}"
                       class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white rounded-xl
                              transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5"
                       style="background: linear-gradient(135deg, #4F46E5, #3B82F6);">
                        📚 Lihat Daftar Buku
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Judul Buku</th>
                                <th>Tgl. Pinjam</th>
                                <th>Tgl. Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($peminjamans as $peminjaman)
                                <tr>
                                    <td class="text-gray-400 font-mono text-xs">{{ $loop->iteration }}</td>

                                    <td>
                                        <span class="font-medium text-indigo-700">
                                            {{ $peminjaman->buku->judul }}
                                        </span>
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
