
<x-siswa-layout>

    <div class="p-6">

        {{-- JUDUL HALAMAN --}}
        <h1 class="text-2xl font-bold text-gray-800">
            Dashboard Siswa
        </h1>

        {{-- INFORMASI PENGGUNA --}}
        <p class="mt-2 text-gray-600">
            Selamat datang di Sistem Informasi Perpustakaan.
        </p>

        {{-- MENU UTAMA --}}
        <div class="admin-card-grid mt-8">

            {{-- CARD DAFTAR BUKU --}}
            <div class="admin-card">
                <a href="{{ route('siswa.buku.index') }}" class="flex h-full items-center justify-between gap-4">
                    <div>
                        <h2 class="student-dashboard-card-title text-lg font-semibold text-gray-800">Daftar Buku</h2>
                        <span>Jelajahi koleksi buku perpustakaan.</span>
                    </div>
                    <span aria-hidden="true" class="student-dashboard-card-arrow text-3xl font-semibold text-red-800">&rsaquo;</span>
                </a>
            </div>

            {{-- CARD RIWAYAT PEMINJAMAN --}}
            <div class="admin-card">
                <a href="{{ route('siswa.peminjaman.index') }}" class="flex h-full items-center justify-between gap-4">
                    <div>
                        <h2 class="student-dashboard-card-title text-lg font-semibold text-gray-800">Peminjaman Saya</h2>
                        <span>Lihat status dan riwayat peminjaman.</span>
                    </div>
                    <span aria-hidden="true" class="student-dashboard-card-arrow text-3xl font-semibold text-red-800">&rsaquo;</span>
                </a>
            </div>

            <div class="admin-card">
                <div class="student-dashboard-borrowed-content">
                    <div>
                        <h2 class="student-dashboard-card-title text-lg font-semibold text-gray-800">Buku Dipinjam</h2>
                        <span>Jumlah buku yang sedang Anda pinjam.</span>
                    </div>
                    <p class="student-dashboard-borrowed-count">{{ $borrowedBooksCount }}</p>
                </div>
            </div>
        </div>

    </div>

</x-siswa-layout>
