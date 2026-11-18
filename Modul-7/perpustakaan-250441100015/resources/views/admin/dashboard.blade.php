<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <span class="text-xl">🛡️</span>
            <h2 class="font-bold text-xl text-gray-800">Dashboard Admin</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Welcome Banner -->
            <div class="rounded-2xl p-8 text-white shadow-xl relative overflow-hidden"
                 style="background: linear-gradient(135deg, #3730a3 0%, #4F46E5 50%, #2563EB 100%);"
                 data-aos="fade-down" data-aos-duration="700">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full translate-x-1/3 -translate-y-1/3"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -translate-x-1/3 translate-y-1/3"></div>
                <div class="relative z-10">
                    <p class="text-indigo-200 text-sm font-medium mb-1">Selamat datang kembali,</p>
                    <h1 class="text-3xl font-bold mb-2">{{ Auth::user()->name }} 👋</h1>
                    <p class="text-indigo-200 text-sm">
                        Anda masuk sebagai <strong class="text-white">Administrator</strong>.
                        Gunakan panel ini untuk mengelola seluruh sistem perpustakaan.
                    </p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6" data-aos="fade-up" data-aos-duration="700" data-aos-delay="200">
                <h3 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
                    <span>⚡</span> Aksi Cepat
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center gap-4 p-4 rounded-xl border-2 border-dashed border-indigo-200 hover:border-indigo-400 hover:bg-indigo-50 transition-all duration-200 group">
                        <span class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center text-xl group-hover:bg-indigo-200 transition-colors">👥</span>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Manajemen Pengguna</p>
                            <p class="text-xs text-gray-500">Kelola akun & role</p>
                        </div>
                    </a>

                    <a href="{{ route('peminjaman.index') }}"
                       class="flex items-center gap-4 p-4 rounded-xl border-2 border-dashed border-emerald-200 hover:border-emerald-400 hover:bg-emerald-50 transition-all duration-200 group">
                        <span class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center text-xl group-hover:bg-emerald-200 transition-colors">📋</span>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Manajemen Peminjaman</p>
                            <p class="text-xs text-gray-500">Setujui & tolak pengajuan</p>
                        </div>
                    </a>

                    <a href="{{ route('buku.index') }}"
                       class="flex items-center gap-4 p-4 rounded-xl border-2 border-dashed border-blue-200 hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 group">
                        <span class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-xl group-hover:bg-blue-200 transition-colors">📖</span>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Kelola Buku</p>
                            <p class="text-xs text-gray-500">Tambah, edit, hapus buku</p>
                        </div>
                    </a>

                    <a href="{{ route('laporan-perpustakaan.index') }}"
                       class="flex items-center gap-4 p-4 rounded-xl border-2 border-dashed border-purple-200 hover:border-purple-400 hover:bg-purple-50 transition-all duration-200 group">
                        <span class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center text-xl group-hover:bg-purple-200 transition-colors">📊</span>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Laporan Perpustakaan</p>
                            <p class="text-xs text-gray-500">Statistik & rekapitulasi</p>
                        </div>
                    </a>

                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-4 p-4 rounded-xl border-2 border-dashed border-gray-200 hover:border-gray-400 hover:bg-gray-50 transition-all duration-200 group">
                        <span class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-xl group-hover:bg-gray-200 transition-colors">⚙️</span>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Pengaturan Profil</p>
                            <p class="text-xs text-gray-500">Edit info & password</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
