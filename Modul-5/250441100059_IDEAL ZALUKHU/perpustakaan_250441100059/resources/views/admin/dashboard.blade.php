<x-admin-layout>

    <div class="admin-welcome">
        <h1 class="admin-page-title">
            Dashboard Admin
        </h1>

        <p>
            Selamat datang, {{ Auth::user()->name }}!
            Kelola sistem informasi perpustakaan melalui menu di sebelah kiri.
        </p>
    </div>

    <h2 class="admin-section-title">
        Ringkasan Sistem
    </h2>

    <div class="admin-card-grid">

        <div class="admin-card">
            <h3>Total Buku</h3>
            <p>{{ \App\Models\Buku::count() }}</p>
            <span>Jumlah koleksi buku</span>
        </div>

        <div class="admin-card">
            <h3>Total Pengguna</h3>
            <p>{{ \App\Models\User::count() }}</p>
            <span>Jumlah akun terdaftar</span>
        </div>

        <div class="admin-card">
            <h3>Total Peminjaman</h3>
            <p>{{ \App\Models\Peminjaman::count() }}</p>
            <span>Jumlah transaksi peminjaman</span>
        </div>

    </div>

    <div class="admin-information">
        <h3>Informasi Sistem</h3>
        <p>
            Anda login sebagai Administrator.
            Gunakan menu navigasi untuk mengelola buku,
            pengguna, peminjaman, dan laporan perpustakaan.
        </p>
    </div>

</x-admin-layout>
