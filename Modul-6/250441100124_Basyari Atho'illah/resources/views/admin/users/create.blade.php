<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    Tambah Pengguna & Tetapkan Role
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Buat akun baru dan pilih hak akses resmi (Admin, Petugas, atau Siswa).
                </p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-xl text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 transition">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="w-full rounded-xl border-slate-300 shadow-2xs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5"
                            placeholder="Contoh: Muhammad Ali">
                        @error('name')
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Alamat Email <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="w-full rounded-xl border-slate-300 shadow-2xs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5"
                            placeholder="nama@perpustakaan.test">
                        @error('email')
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Penetapan Role (Ketentuan RBAC) -->
                    <div>
                        <label for="role" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Penetapan Role Hak Akses <span class="text-rose-500">*</span>
                        </label>
                        <select id="role" name="role" required
                            class="w-full rounded-xl border-slate-300 shadow-2xs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5">
                            <option value="">-- Pilih Role Pengguna --</option>
                            @foreach ($roles as $r)
                                <option value="{{ $r->name }}" {{ old('role') == $r->name ? 'selected' : '' }}>
                                    Role {{ ucfirst($r->name) }} &mdash;
                                    @if ($r->name === 'admin') Akses Penuh Sistem & Pengguna
                                    @elseif ($r->name === 'petugas') Akses Operasional & Sirkulasi Buku
                                    @else Akses Peminjaman Mandiri Siswa
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-[11px] text-slate-400">
                            Role menentukan menu dan otorisasi fitur yang bisa dibuka oleh pengguna.
                        </p>
                        @error('role')
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kata Sandi -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Kata Sandi <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" id="password" name="password" required
                                class="w-full rounded-xl border-slate-300 shadow-2xs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5"
                                placeholder="Minimal 8 karakter">
                            @error('password')
                                <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Konfirmasi Sandi <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="w-full rounded-xl border-slate-300 shadow-2xs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5"
                                placeholder="Ulangi kata sandi">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 text-xs font-semibold rounded-xl text-slate-600 hover:bg-slate-100 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2.5 text-xs font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition">
                            <i class="fa-solid fa-check me-1.5"></i> Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
