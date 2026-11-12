<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku - Perpustakaan Digital UTM</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col justify-between">

    <div>
        <!-- Header Navigasi -->
        <header class="bg-[#111827] text-white shadow-md">
            <div class="max-w-6xl mx-auto px-6 py-4 flex flex-wrap justify-between items-center gap-4">
                <div>
                    <a href="/" class="text-2xl font-bold block hover:text-gray-200">Perpustakaan Digital UTM</a>
                    <nav class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-gray-300 mt-1">
                        <a href="/" class="hover:text-white">Beranda</a>
                        <a href="{{ route('buku.index') }}" class="hover:text-white font-semibold text-white">Daftar Buku</a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="hover:text-white">Dashboard</a>
                            @role('admin|petugas')
                                <a href="{{ route('peminjaman.index') }}" class="hover:text-white">Peminjaman</a>
                                <a href="{{ route('laporan.index') }}" class="hover:text-white">Laporan</a>
                            @endrole
                            @role('admin')
                                <a href="{{ route('admin.users.index') }}" class="hover:text-white">Kelola Pengguna</a>
                            @endrole
                            @role('siswa')
                                <a href="{{ route('peminjaman.riwayat', Auth::id()) }}" class="hover:text-white">Riwayat Pinjam</a>
                            @endrole
                        @endauth
                    </nav>
                </div>

                <!-- Bagian Profil & Login -->
                <div class="flex items-center space-x-3">
                    @auth
                        <span class="text-xs bg-gray-700 text-yellow-300 px-2.5 py-1 rounded font-medium">
                            Role: {{ Auth::user()->roles->pluck('name')->first() ?? 'User' }}
                        </span>
                        <a href="{{ route('profile.edit') }}" class="text-sm text-gray-200 hover:text-white underline">
                            Profil ({{ Auth::user()->name }})
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-sm transition font-medium">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium transition">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm font-medium transition">
                            Register
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Konten Utama -->
        <main class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-md mt-8">
            <div class="flex flex-wrap items-center justify-between border-b pb-4 mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Katalog Data Buku</h1>
                    <p class="text-gray-600 text-sm mt-1">Daftar buku perpustakaan yang tersedia untuk dipinjam atau dikelola.</p>
                </div>

                {{-- SOAL 3: Tombol Tambah Buku dibungkus Gate @can('kelola-buku') --}}
                @can('kelola-buku')
                    <a href="{{ route('buku.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium transition shadow flex items-center gap-1">
                        + Tambah Buku Baru
                    </a>
                @endcan
            </div>

            <!-- Pesan Flash -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded text-sm font-medium mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded text-sm font-medium mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form Pencarian Sederhana -->
            <form action="{{ route('buku.index') }}" method="GET" class="mb-6 flex gap-2">
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari judul buku atau penulis..." class="rounded border-gray-300 shadow-sm p-2 border flex-1 text-sm">
                <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded text-sm font-medium transition">
                    Cari
                </button>
                @if(request('cari'))
                    <a href="{{ route('buku.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-2 rounded text-sm font-medium transition">
                        Reset
                    </a>
                @endif
            </form>

            <!-- Tabel Daftar Buku -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">No</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Kategori</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Judul Buku</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Penulis</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Tahun</th>
                            
                            <!-- SOAL 3: Kolom Aksi hanya tampil jika memiliki Gate 'kelola-buku' -->
                            @can('kelola-buku')
                                <th class="px-4 py-3 text-center font-medium text-gray-600">Aksi</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($bukus as $index => $buku)
                            <tr>
                                <td class="px-4 py-3 text-gray-500">
                                    {{ ($bukus->currentPage() - 1) * $bukus->perPage() + $index + 1 }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="bg-blue-100 text-blue-800 px-2.5 py-0.5 rounded text-xs font-semibold">
                                        {{ $buku->kategori->nama ?? $buku->kategori->nama_kategori ?? 'Umum' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-medium">
                                    <a href="{{ route('buku.show', $buku->id) }}" class="text-blue-600 hover:underline">
                                        {{-- SOAL 2 (a): Escaping Blade aman dari XSS --}}
                                        {{ $buku->judul }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-gray-700">{{ $buku->penulis }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $buku->tahun_terbit }}</td>

                                <!-- SOAL 3 & SOAL 2 (c): Aksi Ubah dan Form Hapus DELETE ber-CSRF -->
                                @can('kelola-buku')
                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('buku.edit', $buku->id) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white text-xs px-2.5 py-1.5 rounded transition">
                                                Ubah
                                            </a>
                                            {{-- Form Hapus menggunakan method DELETE dan @csrf --}}
                                            <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus buku \'{{ addslashes($buku->judul) }}\'?')" class="bg-red-600 hover:bg-red-700 text-white text-xs px-2.5 py-1.5 rounded transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endcan
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                    Belum ada data buku yang tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $bukus->links() }}
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-[#111827] text-white text-center py-4 mt-8">
        <p class="text-sm">&copy; 2026 Perpustakaan Digital UTM. All rights reserved.</p>
    </footer>

</body>
</html>
