<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-600 flex items-center justify-center text-base">
                        <i class="fa-solid fa-users-gear"></i>
                    </span>
                    Kelola Pengguna & Penetapan Role
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Atur akun pengguna, kredensial, dan hak akses (Admin, Petugas, Siswa) pada sistem perpustakaan.
                </p>
            </div>
            <div>
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition">
                    <i class="fa-solid fa-user-plus"></i> Tambah Pengguna Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50/75 text-xs uppercase tracking-wider text-slate-400 font-semibold border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">Nama & Profil</th>
                                <th class="px-6 py-3.5">Email Akun</th>
                                <th class="px-6 py-3.5">Role Terdaftar</th>
                                <th class="px-6 py-3.5">Tanggal Dibuat</th>
                                <th class="px-6 py-3.5 text-right">Aksi Manajemen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($users as $user)
                                @php
                                    $role = $user->roles->first()?->name ?? 'Belum ada role';
                                    $roleBadge = match($role) {
                                        'admin' => 'bg-amber-100 text-amber-800 border-amber-300',
                                        'petugas' => 'bg-sky-100 text-sky-800 border-sky-300',
                                        'siswa' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                        default => 'bg-slate-100 text-slate-700 border-slate-300',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 font-bold flex items-center justify-center text-xs">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-slate-800">{{ $user->name }}</div>
                                                @if ($user->id === auth()->id())
                                                    <span class="text-[10px] text-indigo-600 font-semibold">(Akun Anda)</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-700 font-mono text-xs">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold uppercase tracking-wider rounded-full border {{ $roleBadge }}">
                                            <i class="fa-solid fa-shield text-[10px]"></i> {{ $role }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-400">
                                        {{ $user->created_at?->translatedFormat('d M Y, H:i') ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="inline-flex items-center gap-1.5">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 transition">
                                                <i class="fa-solid fa-pen-to-square text-slate-500"></i> Edit / Ubah Role
                                            </a>

                                            @if ($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium text-rose-700 bg-rose-50 hover:bg-rose-100 transition">
                                                        <i class="fa-solid fa-trash-can"></i> Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($users->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
