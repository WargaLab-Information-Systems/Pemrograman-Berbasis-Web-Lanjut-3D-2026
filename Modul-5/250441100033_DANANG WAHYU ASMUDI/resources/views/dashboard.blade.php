<x-app-layout>
    <div class="bg-white p-6 rounded-lg shadow-md max-w-4xl mx-auto space-y-6">
        <div class="border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Siswa</h1>
            <p class="text-gray-600 mt-1 text-sm">Selamat datang, <span class="font-semibold text-gray-800">{{ Auth::user()->name }}</span>. Anda masuk sebagai <span class="bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded text-xs font-semibold">Siswa</span>.</p>
        </div>

        <div>
            <h2 class="text-lg font-bold text-gray-800 mb-3">Menu Siswa</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('buku.index') }}" class="p-4 bg-gray-50 border rounded-lg hover:bg-gray-100 transition block">
                    <div class="font-bold text-gray-800 mb-1">Katalog Buku</div>
                    <p class="text-xs text-gray-500">Jelajahi koleksi buku dan ajukan pinjaman.</p>
                </a>
                <a href="{{ route('peminjaman.riwayat') }}" class="p-4 bg-yellow-50 border-2 border-yellow-300 rounded-lg hover:bg-yellow-100 transition block">
                    <div class="font-bold text-yellow-900 mb-1">Riwayat Peminjaman</div>
                    <p class="text-xs text-gray-600">Pantau status persetujuan & pengembalian buku Anda.</p>
                </a>
                <a href="{{ route('profile.edit') }}" class="p-4 bg-gray-50 border rounded-lg hover:bg-gray-100 transition block">
                    <div class="font-bold text-gray-800 mb-1">Profil Saya</div>
                    <p class="text-xs text-gray-500">Perbarui data diri dan kata sandi akun.</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
