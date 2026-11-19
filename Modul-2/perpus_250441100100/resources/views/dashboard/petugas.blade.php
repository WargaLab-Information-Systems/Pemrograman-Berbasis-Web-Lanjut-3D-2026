<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Petugas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold">
                        Selamat Datang, {{ auth()->user()->name }}
                    </h3>

                    <p class="mt-2 text-gray-600">
                        Anda dapat mengelola data buku, peminjaman,
                        pengembalian, dan laporan perpustakaan.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-500">
                            Total Buku
                        </p>

                        <h3 class="text-3xl font-bold mt-2">
                            {{ $totalBuku }}
                        </h3>

                        <a href="{{ route('buku.index') }}"
                           class="inline-block mt-4 text-blue-600 hover:text-blue-800">
                            Lihat Daftar Buku
                        </a>
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-500 mb-3">
                            Operasional Perpustakaan
                        </p>

                        <div class="flex flex-col gap-2">
                            <a href="{{ route('peminjaman.index') }}"
                               class="text-blue-600 hover:text-blue-800">
                                Kelola Peminjaman ({{ $menunggu }} menunggu)
                            </a>

                            <a href="{{ route('pengembalian.index') }}"
                               class="text-blue-600 hover:text-blue-800">
                                Catat Pengembalian
                            </a>

                            <a href="{{ route('laporan.index') }}"
                               class="text-blue-600 hover:text-blue-800">
                                Lihat Laporan
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="bg-white shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">

                    <h3 class="text-lg font-semibold mb-4">
                        Buku Terbaru
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full">

                            <thead class="border-b">
                                <tr>
                                    <th class="px-4 py-3 text-left">
                                        Judul Buku
                                    </th>

                                    <th class="px-4 py-3 text-left">
                                        Kategori
                                    </th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse ($bukuTerbaru as $buku)
                                    <tr class="border-b">

                                        <td class="px-4 py-3">
                                            {{ $buku->judul }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ $buku->kategori?->nama ?? 'Tanpa Kategori' }}
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2"
                                            class="px-4 py-3 text-center text-gray-500">
                                            Belum ada data buku.
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>

                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>