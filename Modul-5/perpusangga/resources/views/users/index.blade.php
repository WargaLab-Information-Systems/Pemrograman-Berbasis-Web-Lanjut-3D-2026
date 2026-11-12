<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl text-slate-800 leading-tight">Kelola Role Pengguna</h2>
                <p class="text-xs text-slate-500 mt-0.5">Atur hak akses pengguna (Admin, Petugas, Siswa)</p>
            </div>
            <span class="px-3 py-1 rounded-lg text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">Hak Akses</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white border border-slate-200/80 rounded-xl p-6 shadow-sm">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Daftar Akun Pengguna</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50 text-xs font-bold text-slate-500 uppercase">
                                <th class="py-3 px-4">#</th>
                                <th class="py-3 px-4">Nama</th>
                                <th class="py-3 px-4">Email</th>
                                <th class="py-3 px-4">Role Saat Ini</th>
                                <th class="py-3 px-4">Ubah Role</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                            @foreach($users as $index => $user)
                                <tr>
                                    <td class="py-3.5 px-4 font-semibold text-slate-400">{{ $index + 1 }}</td>
                                    <td class="py-3.5 px-4 font-medium text-slate-900">{{ $user->name }}</td>
                                    <td class="py-3.5 px-4 text-slate-600">{{ $user->email }}</td>
                                    <td class="py-3.5 px-4">
                                        @forelse($user->getRoleNames() as $roleName)
                                            @if($roleName === 'admin')
                                                <span class="px-2.5 py-1 rounded text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200 uppercase">Admin</span>
                                            @elseif($roleName === 'petugas')
                                                <span class="px-2.5 py-1 rounded text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">Petugas</span>
                                            @else
                                                <span class="px-2.5 py-1 rounded text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 uppercase">Siswa</span>
                                            @endif
                                        @empty
                                            <span class="px-2.5 py-1 rounded text-xs font-bold bg-slate-100 text-slate-500">Tanpa Role</span>
                                        @endforelse
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <form action="{{ route('users.updateRole', $user->id) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            <select name="role" class="border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs text-slate-800 focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                        {{ ucfirst($role->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" onclick="return confirm('Ubah role pengguna ini?')" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-sm transition active:scale-[0.98]">
                                                Simpan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>