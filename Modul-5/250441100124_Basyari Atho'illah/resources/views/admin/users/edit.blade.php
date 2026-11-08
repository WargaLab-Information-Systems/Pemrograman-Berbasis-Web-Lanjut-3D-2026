<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    Edit Akun & Ubah Role Pengguna
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Memperbarui data akun untuk: <strong>{{ $user->name }}</strong> ({{ $user->email }})
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
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full rounded-xl border-slate-300 shadow-2xs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5">
                        @error('name')
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Alamat Email <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full rounded-xl border-slate-300 shadow-2xs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5">
                        @error('email')
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Penetapan Role (Ketentuan RBAC Skenario 4) -->
                    <div>
                        <label for="role" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Role Hak Akses <span class="text-rose-500">*</span>
                        </label>
                        @php
                            $currentRole = $user->roles->first()?->name;
                        @endphp
                        <select id="role" name="role" required
                            class="w-full rounded-xl border-slate-300 shadow-2xs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5">
                            @foreach ($roles as $r)
                                <option value="{{ $r->name }}" {{ old('role', $currentRole) == $r->name ? 'selected' : '' }}>
                                    Role {{ ucfirst($r->name) }} &mdash;
                                    @if ($r->name === 'admin') Akses Penuh Sistem & Pengguna
                                    @elseif ($r->name === 'petugas') Akses Operasional & Sirkulasi Buku
                                    @else Akses Peminjaman Mandiri Siswa
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-[11px] text-slate-400">
                            Mengubah role ini akan langsung menyesuaikan izin akses pengguna saat login.
                        </p>
                        @error('role')
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ganti Kata Sandi (Opsional) -->
                    <div class="pt-4 border-t border-slate-100">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
                            Ubah Kata Sandi (Kosongkan bila tidak ingin diubah)
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-xs font-medium text-slate-700 mb-1">
                                    Kata Sandi Baru
                                </label>
                                <input type="password" id="password" name="password"
                                    class="w-full rounded-xl border-slate-300 shadow-2xs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5"
                                    placeholder="Biarkan kosong jika tetap">
                                @error('password')
                                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-xs font-medium text-slate-700 mb-1">
                                    Ulangi Sandi Baru
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="w-full rounded-xl border-slate-300 shadow-2xs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5"
                                    placeholder="Konfirmasi sandi baru">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 text-xs font-semibold rounded-xl text-slate-600 hover:bg-slate-100 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2.5 text-xs font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition">
                            <i class="fa-solid fa-save me-1.5"></i> Perbarui Data & Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
