<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Akun & Role Pengguna (Admin)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Daftar Pengguna Sistem</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Nama</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Email</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Role Saat Ini</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Ubah Role</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-xs text-gray-700">
                                        @foreach ($user->roles as $role)
                                            <span class="font-medium">{{ ucfirst($role->name) }}</span>{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('admin.users.role', $user->id) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            <select name="role" class="text-xs border-gray-300 rounded shadow-sm focus:border-gray-400 focus:ring-0 py-1">
                                                @foreach ($roles as $r)
                                                    <option value="{{ $r->name }}" {{ $user->hasRole($r->name) ? 'selected' : '' }}>
                                                        {{ ucfirst($r->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="px-2.5 py-1 text-xs border border-gray-300 rounded text-gray-700 hover:bg-gray-100">
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

