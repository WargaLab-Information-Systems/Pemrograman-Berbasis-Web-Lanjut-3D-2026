
<x-petugas-layout>

    <div class="admin-welcome">
        <h1>Dashboard Petugas</h1>

        <p>
            Selamat datang, {{ Auth::user()->name }}.
            Anda berhasil masuk ke sistem sebagai Petugas.
            Silakan gunakan menu di sebelah kiri untuk mengelola
            buku dan peminjaman perpustakaan.
        </p>
    </div>

    <h2 class="admin-section-title">
        Menu Petugas
    </h2>

    <div class="admin-card-grid">

        <a href="{{ route('petugas.buku.index') }}"
           class="admin-card petugas-dashboard-card">
            <div>
                <h3>Manajemen Buku</h3>
                <span>Kelola dan lihat data buku perpustakaan.</span>
            </div>
            <span aria-hidden="true" class="petugas-dashboard-card-arrow">&rsaquo;</span>
        </a>

        <a href="{{ route('petugas.peminjaman.index') }}"
           class="admin-card petugas-dashboard-card">
            <div>
                <h3>Manajemen Peminjaman</h3>
                <span>Kelola transaksi peminjaman dan pengembalian.</span>
            </div>
            <span aria-hidden="true" class="petugas-dashboard-card-arrow">&rsaquo;</span>
        </a>

        <a href="{{ route('petugas.laporan.index') }}"
           class="admin-card petugas-dashboard-card">
            <div>
                <h3>Laporan</h3>
                <span>Lihat laporan aktivitas perpustakaan.</span>
            </div>
            <span aria-hidden="true" class="petugas-dashboard-card-arrow">&rsaquo;</span>
        </a>

    </div>

</x-petugas-layout>
